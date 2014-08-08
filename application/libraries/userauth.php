<?php

define('COOKIE_AUTH', 'Rw9x_auth');//用户认证cookie 登录后用户名 id dateline 数组序列化后加密
define('COOKIE_AUTOLOGIN', 'Rw9x_autolog'); //用户自动登录 cookie  登录用户id keys dateline 数组序列化后加密

define('LAST_ACTIVE', 'Rw9x_lastactive');
define('ADMIN_VERCODE_COOKIE', 'Rw9x_vercode');	//后台验证码

define('AUTOLOGIN_TIME', 60 * 60 * 24 * 30);	//自动登录记录时间

class Userauth
{
	private $_CI;
	
	function __construct()
	{
		$this->_CI =& get_instance();
		
		$this->_CI->load->model('autologin_model', 'autologinmod');
		$this->_CI->load->model('admin_model', 'adminmod');
	}
	
	function checklogin()
	{
		$usercookie = get_cookie(COOKIE_AUTH, TRUE);
		if (! empty($usercookie))
		{
			$usercookie = unserialize(authcode($usercookie, 'DECODE'));		//解密
			if (! empty($usercookie) && isset($usercookie['uid']) && isset($usercookie['username']))
			{
				$user = $this->_CI->adminmod->fetchone($usercookie['uid']);
				
				if (! empty($user) && $user['username'] == $usercookie['username'])
				{
					return $user;
				}
			}
		}
		
		return $this->autologin();
	}
	
	function autologin()
	{
		$autock = get_cookie(COOKIE_AUTOLOGIN, TRUE);
		
		if (! empty($autock))
		{
			$autock = unserialize(authcode($autock, 'DECODE'));		//解密
			if (! empty($autock) && isset($autock['uid']) && isset($autock['keys']))
			{
				$auto = $this->_CI->autologinmod->find_by_id($autock['keys']);
				if (! empty($auto) && $auto['uid'] == $autock['uid'])
				{
					$user = $this->_CI->adminmod->fetchone($auto['uid']);
					if (! empty($user))
					{
						$this->createauthcookie($user['uid'], $user['username']);

						return $user;
					}
				}
			}
		}
		return FALSE;
	}
	
	function auth($username, $password, $remeber = FALSE)
	{
		$user = $this->_CI->adminmod->auth($username, $password);
		if (! empty($user))
		{
			$login = array(
				'uid'		=> $user['uid'],
				'lastlogin'	=> time(),
				'lastip'	=> $this->_CI->input->ip_address(),
			);
			$this->_CI->adminmod->commit($login);
			
			$this->createauthcookie($user['uid'], $user['username']);
			if ($remeber)
			{
				$this->createautologin($user['uid']);
			}
			return $user;
		}
		return FALSE;
	}
	
	function logout()
	{
		$this->deleteautologin();
		delete_cookie(COOKIE_AUTH);
	}
	
	function createauthcookie($uid, $username)
	{
		$data = array(
			'uid'		=> $uid,
			'username'	=> $username,
			'dateline'	=> time(),
		);
		set_cookie(COOKIE_AUTH, authcode(serialize($data), 'ENCODE'), 0);
	}
	
	function createautologin($uid)
	{
		$this->_CI->autologinmod->delete_by_uid($uid);
		$keys = random_string('unique');
		$data = array(
			'keys'		=> $keys,
			'uid'		=> $uid,
			'user_agent'	=> $this->_CI->input->user_agent(),
			'lastip'	=> $this->_CI->input->ip_address(),
			'lastlogin'	=> time(),
		);
		
		$this->_CI->autologinmod->commit($data);	//数据库记录登录
		$cookie = array(
			'uid'	=> $uid,
			'keys'	=> $keys,
			'dateline'	=> time(),
		);
		set_cookie(COOKIE_AUTOLOGIN, authcode(serialize($cookie), 'ENCODE'), AUTOLOGIN_TIME);
	}
	
	function deleteautologin()
	{
		$autock = get_cookie(COOKIE_AUTOLOGIN, TRUE);
		if (! empty($autock))
		{
			$autock = unserialize(authcode($autock, 'DECODE'));		//解密
			if (! empty($autock) && isset($autock['uid']) && isset($autock['keys']))
			{
				$this->_CI->autologinmod->delete_by_uid($autock['uid']);
				delete_cookie(COOKIE_AUTOLOGIN);
			}
		}
	}
	
	function createadmin($username, $email, $password, $groupid = 1)
	{
		$admin = array(
			'username'		=> $username,
			'email'			=> $email,
			'groupid'		=> $groupid,
			'valid'			=> 1,
			'regtime'		=> time(),
		);
		$admin['salt'] = $salt = random_string('alnum', 12);
		$admin['password'] = md5(md5($password) . $salt);
		$this->_CI->adminmod->commit($admin);
		
		return true;
	}
}