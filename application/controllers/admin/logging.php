<?php

class Logging extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

	}
	
	function index()
	{
		$this->settitle('登录');
		$this->load->view('admin/logging', $this->template);
	}
	
	/**
	 * 1 成功
	 * 2 失败
	 * 3 用户名为空
	 * 4 密码为空
	 * 5 验证码为空
	 * 6 验证码错误
	 * 7 验证码过期
	 */
	function auth()
	{
		$input = $this->input->post('user', TRUE);
		
		$vercookie = $this->input->cookie(ADMIN_VERCODE_COOKIE);
		$verdata = @unserialize(authcode($vercookie, 'DECODE'));
		if ((! isset($verdata['dateline']) || ! isset($verdata['expired']) || ! isset($verdata['vercode'])) && ($verdata['dateline'] + $verdata['expired']) < time())
		{
			echo 7;
		} else if (empty($input['vercode']))
		{
			echo 5;
		} else if (strtolower($verdata['vercode']) != strtolower($input['vercode']))
		{
			echo 6;
		} else if (empty($input['username']))
		{
			echo 3;
		} else if (empty($input['password']))
		{
			echo 4;
		} else 
		{
			delete_cookie(ADMIN_VERCODE_COOKIE);
			$user = $this->userauth->auth($input['username'], $input['password']);
			if (empty($user))
			{
				echo 2;
			} else 
			{
				set_cookie(LAST_ACTIVE, time(), 0);
				echo 1;
			}
		}
	}
	
	function logout()
	{
		$this->userauth->logout();
		redirect('');
	}
	
	function vercode()
	{
		$this->load->library('vercode');
		
		$data = array(
				'vercode'	=> $this->vercode->get_code(),
				'dateline'	=> time(),
				'expired'	=> 30,		//验证码30S过期
			);
		$str = serialize($data);
		set_cookie(ADMIN_VERCODE_COOKIE, authcode($str, 'ENCODE'), 0);
		$this->vercode->echo_image();
	}
}