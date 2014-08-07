<?php if(!defined('BASEPATH')) die('No direct script access allowed!');


class Vercode
{
	public static $SUCCESS = 1;
	public static $INCORRECT = 2;		
	public static $TIMEOUT = 3;
	
	private $font;
	private $font_size;
	
	private $img_height;
	private $img_width;
	private $use_boder;
	private $filter_type;
	
	private $code;
	private $charset = 'abcdefghkmnprstuvwyzABCDEFGHKLMNPRSTUVWYZ23456789';
	
	private $code_len;
	
	private $cookie_name;
	private $auth_key;
	private $timeout;
	
	function __construct()
	{
		$this->font = realpath('pub/font/ggbi.ttf');
		$this->font_size = 14;
		$this->img_height = 24;
		$this->img_width = 68;
		$this->use_boder = true;
		$this->filter_type = 0;
		$this->code_len = 4;
		
		$this->cookie_name = 'ver_code';
		$this->auth_key = 'bfTNGb7o7GMz5VPq';
		$this->timeout = 0;
		
		$this->create_code();
	}

	/**
	 * 生成随机验证码。
	 */
	function create_code() 
	{
		$code = '';
		$charset_len = strlen($this->charset) - 1;
		for ($i=0; $i<$this->code_len; $i++) 
		{
			$code .= $this->charset[rand(1, $charset_len)];
		}
		
		$this->code = $code;
	}
	
	function get_code()
	{
		return $this->code;
	}
	
	function set_timeout($timeout)
	{
		$this->timeout = $timeout;
	}
	

	function echo_image()
	{
//		$this->write_cookie();
		
		//创建图片，并设置背景色
		$im = @imagecreate($this->img_width, $this->img_height);
		imagecolorallocate($im, 255,255,255);

		//文字随机颜色
		$fontColor[]  = imagecolorallocate($im, 0x15, 0x15, 0x15);
		$fontColor[]  = imagecolorallocate($im, 0x95, 0x1e, 0x04);
		$fontColor[]  = imagecolorallocate($im, 0x93, 0x14, 0xa9);
		$fontColor[]  = imagecolorallocate($im, 0x12, 0x81, 0x0a);
		$fontColor[]  = imagecolorallocate($im, 0x06, 0x3a, 0xd5);

		//背景横线
		$lineColor1 = imagecolorallocate($im, 0xda, 0xd9, 0xd1);
		for($j=3; $j<=$this->img_height-3; $j=$j+3)
		{
			imageline($im, 2, $j, $this->img_width - 2, $j, $lineColor1);
		}

		//背景竖线
		$lineColor2 = imagecolorallocate($im, 0xda,0xd9,0xd1);
		for($j=2;$j<100;$j=$j+6)
		{
			imageline($im, $j, 0, $j+8, $this->img_height, $lineColor2);
		}

		//画边框
		if( $this->use_boder && $this->filter_type == 0 )
		{
			$bordercolor = imagecolorallocate($im, 0x9d, 0x9e, 0x96);
			imagerectangle($im, 0, 0, $this->img_width-1, $this->img_height-1, $bordercolor);
		}

		//输出文字
		for($i = 0; $i < strlen($this->code); $i++)
		{
			$bc = mt_rand(0, 1);
			$c_fontColor = $fontColor[mt_rand(0,4)];
			$y_pos = $i==0 ? 4 : $i*($this->font_size+2);
			$c = mt_rand(0, 15);
			@imagettftext($im, $this->font_size, $c, $y_pos, 19, $c_fontColor, $this->font, $this->code[$i]);
		}

		//图象效果
		switch($this->filter_type)
		{
			case 1:
				imagefilter ($im, IMG_FILTER_NEGATE);
				break;
			case 2:
				imagefilter ($im, IMG_FILTER_EMBOSS);
				break;
			case 3:
				imagefilter ($im, IMG_FILTER_EDGEDETECT);
				break;
			default:
				break;
		}

		header("Pragma:no-cache\r\n");
		header("Cache-Control:no-cache\r\n");
		header("Expires:0\r\n");

		//输出特定类型的图片格式，优先级为 jpg ->png
		if(function_exists("imagejpeg"))
		{
			header("content-type:image/jpeg\r\n");
			imagejpeg($im);
		} else
		{
			header("content-type:image/png\r\n");
			imagepng($im);
		}
		imagedestroy($im);
		exit();
	}
	
	function write_cookie()
	{
		$data = array(
			'time'		=> time(),
			'vercode'	=> $this->code,
		);
		
		$cookie = authcode(serialize($data), 'ENCODE');
		set_cookie($this->cookie_name, $cookie, 0);
	}
	
	function check_code($code)
	{
		$cookie = get_cookie($this->cookie_name);
		$data = unserialize(authcode($cookie, 'DECODE'));
		
		if (is_array($data) && isset($data['time']) && isset($data['vercode']))
		{
			if ($this->timeout != 0 && time() > $data['time'] + $this->timeout)
			{
				return self::$TIMEOUT;
			} else if (strtolower($code) == strtolower($data['vercode']))
			{
				return self::$SUCCESS;
			} else 
			{
				return self::$INCORRECT;
			}
		} else 
		{
			return self::$INCORRECT;
		}
	}
	
	public function _authcode($string, $operation = 'DECODE', $expiry = 0) {
		$ckey_length = 4;	// 随机密钥长度 取值 0-32;

		$keya = md5(substr($this->auth_key, 0, 16));
		$keyb = md5(substr($this->auth_key, 16, 16));
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
