<?php

if (! function_exists('P'))
{
	/**
	 * 获取post参数
	 * @param $name 所要取得的post中的数据  如果数据不存在，方法将返回 FALSE (布尔值)
	 * @param $xss_clean 如果想让取得的数据经过跨站脚本过滤（XSS Filtering），把第二个参数设为TRUE
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/input.html
	 */
	function P($name = NULL, $xss_clean = FALSE)
	{
		$CI =& get_instance();
		
		return $CI->input->post($name, $xss_clean);
	}
}

if (! function_exists('G'))
{
	/**
	 * 获取Get参数
	 * @param $name 所要取得的Get中的数据  如果数据不存在，方法将返回 FALSE (布尔值)
	 * @param $xss_clean 如果想让取得的数据经过跨站脚本过滤（XSS Filtering），把第二个参数设为TRUE
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/input.html
	 */
	function G($name = NULL, $xss_clean = FALSE)
	{
		$CI =& get_instance();
		
		return $CI->input->get($name, $xss_clean);
	}
}

if (! function_exists('S'))
{
	/**
	 * 获取$_SERVER值
	 * @param $name 所要取得的Server中的数据  如果数据不存在，方法将返回 FALSE (布尔值)
	 * @param $xss_clean 如果想让取得的数据经过跨站脚本过滤（XSS Filtering），把第二个参数设为TRUE
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/input.html
	 */
	function S($name = NULL, $xss_clean = FALSE)
	{
		$CI =& get_instance();
		
		return $CI->input->server($name, $xss_clean);
	}
}

if (! function_exists('C'))
{
	/**
	 * Cookie 辅助函数
	 * 	
	 * @param  $name
	 * $name 数组格式
	 *  array(
	 *    'name'   => 'The Cookie Name',
	 *    'value'  => 'The Value',
	 *    'expire' => '86500',
	 *    'domain' => '.some-domain.com',
	 *    'path'   => '/',
	 *    'prefix' => 'myprefix_',
	 *    'secure' => TRUE
	 *  );
	 * @param  $value
	 * 		is_bool($value) 则返回$name对应的Cookie值 $value是否xss过滤
	 * 		is_array($name) 或者 is_string($name) 设置cookie值
	 * @param  $expire 
	 * @param  $domain
	 * @param  $path
	 * @param  $prefix
	 * @param  $secure
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/input.html
	 */
	function C($name = '', $value = FALSE, $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE)
	{
		if (is_array($name))
		{
			set_cookie($name);
		} else if (is_string($name))
		{
			if (is_bool($value))
			{
				return get_cookie($name, $value);
			} else if (is_string($name) && is_string($value))
			{
				set_cookie($name, $value, $expire, $domain, $path, $prefix, $secure);
			}
		}
	}
}

if (! function_exists('A'))
{
	/**
	 * IP地址函数
	 * @param $ip
	 * 		设置了$ip 则验证该string是否是合法的IP地址
	 * 		否则返回客户端IP地址串
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/input.html
	 */
	function A($ip = FALSE)
	{
		$CI =& get_instance();
		
		if (is_string($ip))
		{
			return $CI->input->valid_ip($ip);
		}
		return $CI->input->ip_address();
	}
}

if (! function_exists('V'))
{
	/**
	 * 验证类辅助函数
	 * @param  $field
	 * @param  $label
	 * @param  $rules 
	 * 		$rules === TRUE 也就是默认V()则开始验证
	 * 		$rules === FALSE 也就是V('msg-key', 'msg-content')则设置验证错误信息
	 * 		否则设置验证规则
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/form_validation.html
	 */
	function V($field = '', $label = '', $rules = TRUE)
	{
		$CI =& get_instance();
		
		if ($rules === TRUE)
		{
			return $CI->form_validation->run();
		} else if ($rules === FALSE)
		{
			$CI->form_validation->set_message($field, $label);
		} else 
		{
			$CI->form_validation->set_rules($field, $label, $rules);
		}
	}
}

if (! function_exists('U'))
{
	/**
	 * URI 类辅助方法
	 * @param  $n
	 * 		is_array($n) 则返回该数组拼接成url
	 * 		is_int($n) 
	 * 			is_array($no_result) 返回url组合的数组
	 * 			否则返回第$n段url值
	 * 		$n === TRUE 返回url总段数
	 * 		$n === FALSE 返回url段数组
	 * @param  $no_result
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/uri.html
	 */
	function U($n, $no_result = FALSE)
	{
		$CI =& get_instance();
		
		if (is_array($n))
		{
			return $CI->uri->assoc_to_uri($n);
		}
		if (is_int($n))
		{
			if (is_array($no_result))
			{
				return $CI->uri->uri_to_assoc($n, $no_result);
			}
			return $CI->uri->segment($n, $no_result);
		}
		if ($n === TRUE)
		{
			return $CI->uri->total_segments();
		} else if ($n === FALSE)
		{
			return $CI->uri->segment_array();
		}
	}
}

if (! function_exists('PG'))
{
	/**
	 * 分页类辅助函数
	 * @param  $param
	 * 		默认返回创建的链接
	 * 		传入$param 数组参数则执行初始化方法
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/pagination.html
	 */
	function PG($param = TRUE)
	{
		$CI =& get_instance();
		
		if ($param === TRUE)
		{
			return $CI->pagination->create_links();
		}
		$CI->pagination->initialize($param);
	}
}

if (! function_exists('UP'))
{
	/**
	 * 上传类辅助方法
	 * @param  $param
	 * 		is_array($param) 初始化
	 * 		$param === TRUE 返回上传文件数据
	 * 		is_string($param) 开始上传
	 * 		否则($param === FALSE) 显示上传错误信息
	 * @param  $data
	 * 
	 * @link http://codeigniter.org.cn/user_guide/libraries/file_uploading.html
	 */
	function UP($param = '', $data = '')
	{
		$CI =& get_instance();
		
		if (is_array($param))
		{
			$CI->upload->initialize($param);
		} else if ($param === TRUE)
		{
			$CI->upload->data();
		} else if (is_string($param) && strlen($param) > 0 && $data === '')
		{
			$CI->upload->do_upload($param);
		} else 
		{
			$CI->upload->display_errors($param, $data);
		}
	}
}

if (! function_exists('CFG'))
{
	/**
	 * Config 辅助函数
	 * @param $file
	 * @param $use_sections
	 * @param $fail_gracefully
	 */
	function CFG($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
	{
		$CI =& get_instance();

		if ($file == '*s')
		{
			return $CI->config->site_url();
		} else if ($file == '*sys')
		{
			return $CI->config->system_url();
		} else if (stripos($file, '*') === 0)
		{
			$key = trim($file, '*');
			return $CI->config->item($key, $use_sections);
		} else if (is_bool($use_sections))
		{
			return $CI->config->load($file, $use_sections, $fail_gracefully);
		} else if (is_string($use_sections))
		{
			$CI->config->set_item($file, $use_sections);
		}
	}
	
}
