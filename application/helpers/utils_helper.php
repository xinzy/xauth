<?php

if (! function_exists('strlen_utf8'))
{
	function strlength($str)
	{
		$i = 0;
		$count = 0;
		$len = strlen ($str);
		while ($i < $len)
		{
			$chr = ord ($str[$i]);
			$count++;
			$i++;
			if($i >= $len)
				break;

			if($chr & 0x80)
			{
				$chr <<= 1;
				while ($chr & 0x80)
				{
					$i++;
					$chr <<= 1;
				}
			}
		}
		return $count;
	}
}

if (! function_exists('cutstr'))
{
	function cutstr($str, $length, $dot = '...', $charset = "utf-8")
	{
		$str = trim($str); //清除字符串两边的空格
		$str = strip_tags($str, ""); //利用php自带的函数清除html格式
		$str = preg_replace("/\t/", "", $str); //使用正则表达式匹配需要替换的内容，如：空格，换行，并将替换为空。
		$str = preg_replace("/\r\n/", "", $str);
		$str = preg_replace("/\r/", "", $str);
		$str = preg_replace("/\n/", "", $str);
		$str = preg_replace("/ /", "", $str);
		$str = preg_replace("/&nbsp; /", "", $str); //匹配html中的空格
		$str = trim($str); //清除字符串两边的空格

		$strlen = strlength($str);
		if ($strlen < $length)
		{
			return $str;
		}

		if (function_exists("mb_substr"))
		{
			$substr = mb_substr($str, 0, $length, $charset);
		} else
		{
			$c['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$c['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			preg_match_all($c[$charset], $str, $match);
			$substr = join("", array_slice($match[0], 0, $length));
		}

		return $substr . $dot;
	}
}

if (! function_exists('address'))
{
	function address($id, $ret_arr = FALSE)
	{
		$CI =& get_instance();
		$CI->load->model('region_model', 'region');
		
		$addr = $CI->region->fetchAddr($id);
		
		if ($ret_arr)
		{
			return $addr;
		}
		
		if ($addr)
		{
			$addrstr = '';
			foreach ($addr as $item)
			{
				$addrstr .= $item['value'] . ' ';
			}
			return $addrstr;
		} else 
		{
			return '';
		}
	}	
}

if(! function_exists('authcode'))
{
	/**
	 * 加密解密
	 * @param unknown_type $string
	 * @param unknown_type $operation
	 * @param unknown_type $key
	 * @param unknown_type $expiry
	 */
	function authcode($string, $operation = 'DECODE', $key = NULL, $expiry = 0)
	{
		$ci =& get_instance();

		$ckey_length = 4;	// 随机密钥长度 取值 0-32;
		// 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
		// 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
		// 当此值为 0 时，则不产生随机密钥

		empty($key) && $key = $ci->config->item('encryption_key');
		if(empty($key)){
			exit('PARAM $key IS EMPTY! ENCODE/DECODE IS NOT WORK!');
		}else{
			$key = md5($key);
		}

		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}
}

if (! function_exists('ipresolve'))
{
	/**
	 * @param string $ipaddr
	 * @param int $ret_type
	 * 		0  直接输出地址
	 * 		1 直接输出地址和服务商
	 * 		2 返回地址
	 * 		3 返回地址和服务商
	 * 		4 返回数组
	 */
	function ipresolve($ipaddr, $ret_type = 0)
	{
		require_once FCPATH . 'application/third_party/ipdat/ipresolve.php';
		$ip = new Ipresolve();
		$address = $ip->ip2addr($ipaddr);
		
		switch ($ret_type)
		{
		case 0:
			echo $address['country'];
			break;
		case 1:
			echo $address['country'] . $address['area'];
			break;
		case 2:
			return $address['country'];
		case 3: 
			return $address['country'] . $address['area'];
		case 4:
			return $address;
		default:
			echo $address['country'] . $address['area'];
			break;
		}
	}
}

if (! function_exists('cecho'))
{
	/**
	 * 检查数组是否有指定的key并输出
	 */
	function cecho($arr, $key, $default = '')
	{
		if (array_key_exists($key, $arr))
		{
			echo $arr[$key];
		} else 
		{
			echo $default;
		}
	}
}
