<?php

class Xauth
{
	private $CI;
	
	private $store_salt = 'fuck';
	private $salt_length;
	private $hash_method;
	private $default_rounds;
	private $random_rounds;
	private $min_rounds;
	private $max_rounds;

	private $identity_field;
	private $email_activation;
	
	private $lockout_time;
	private $maximum_login_attempts;
	private $track_login_ip_address;
	private $track_login_attempts;
	
	private $identity_cookie_name;
	private $remember_cookie_name;
	
	private $forgot_password_expiration;
	
	function __construct()
	{
		$this->CI =& get_instance();

		CFG('xauth', TRUE);
		$this->store_salt = CFG('*store_salt', 'xauth');
		$this->salt_length = CFG('*salt_length', 'xauth');
		
		$this->hash_method = CFG('*hash_method', 'xauth');
		$this->default_rounds = CFG('*default_rounds', 'xauth');
		$this->random_rounds = CFG('*random_rounds', 'xauth');
		$this->min_rounds = CFG('*min_rounds', 'xauth');
		$this->max_rounds = CFG('*max_rounds', 'xauth');
		
		$this->identity_field = CFG('*identity', 'xauth');
		$this->email_activation = CFG('*email_activation', 'xauth');
		
		$this->lockout_time = CFG('*lockout_time', 'xauth');
		$this->maximum_login_attempts = CFG('*maximum_login_attempts', 'xauth');
		$this->track_login_ip_address = CFG('*track_login_ip_address', 'xauth');
		$this->track_login_attempts = CFG('*track_login_ip_address', 'xauth');
		
		$this->identity_cookie_name = CFG('*identity_cookie_name', 'xauth');
		$this->remember_cookie_name = CFG('*remember_cookie_name', 'xauth');
		
		$this->forgot_password_expiration = CFG('*forgot_password_expiration', 'xauth');
		
		if ($this->hash_method == 'bcrypt') 
		{
			if ($this->random_rounds)
			{
				$rand = rand($this->min_rounds,$this->max_rounds);
				$params = array('rounds' => $rand);
			} else
			{
				$params = array('rounds' => $this->default_rounds);
			}
		
			$params['salt_prefix'] = CFG('*salt_prefix', 'xauth');
			$this->load->library('bcrypt',$params);
		}
		$this->load->model('loginattempts_model', 'loginattempts');
		$this->load->model('users_model', 'usersmod');
		$this->load->library('myemail');

		if (! $this->logged_in() && C($this->identity_cookie_name) && C($this->remember_cookie_name))
		{
			$this->autologin();
		}
	}
	
	function __get($var)
	{
		return $this->CI->$var;
	}
	
	function autologin()
	{
		$remembercode = C($this->remember_cookie_name);
		$identitycookie = C($this->identity_cookie_name);
		
		if (empty($remembercode) || empty($identitycookie))
		{
			return FALSE;
		}
		$identityFiled = explode('|', $this->identity_field);
		
		$user = NULL;
		if (in_array('email', $identityFiled) && valid_email($identitycookie))
		{
			$user = $this->usersmod->fetchby(array('email' => $identitycookie, 'remember_code' => $remembercode));
		} else if (in_array('username', $identityFiled) && ! valid_email($identitycookie))
		{
			$user = $this->usersmod->fetchby(array('username' => $identitycookie, 'remember_code' => $remembercode));
		}
		if (empty($user))
		{
			return FALSE;
		}
		$user = $user[0];
		$this->set_session($user);
		$this->update_last_login($user['uid']);
			
		if (CFG('*remember_users', 'xauth'))
		{
			$this->remember_me($user);
		}
		return TRUE;
	}
	
	/**
	 * @return 
	 * 		TRUE:	成功
	 * 		FALSE:	登陆名密码错误
	 * 		1:		账号未激活
	 * 		2:		超过最大尝试次数
	 */
	function login($identity, $password, $remember = 0)
	{
		if (empty($identity) || empty($password))
		{
			return false;
		}
		
		if ($this->is_max_login_attempts($identity))
		{
			return 2;
		}
		
		$identityFiled = explode('|', $this->identity_field);
		$user = NULL;
		
		if (in_array('username', $identityFiled))
		{
			$user = $this->usersmod->fetchby(array('username' => $identity));
		}
		if (empty($user) && in_array('email', $identityFiled))
		{
			$user = $this->usersmod->fetchby(array('email' => $identity));
		}
		
		if (empty($user))
		{
			return FALSE;
		}
		$user = $user[0];
		$check = $this->hash_password_db($password, $user['password'], $user['salt']);
		if ($check)
		{
			if ($user['active'] == 0 && $this->email_activation)
			{
				return 1;
			}
			$this->set_session($user);
			$this->update_last_login($user['uid']);
			$this->clear_login_attempts($identity);
			
			if ($remember && CFG('*remember_users', 'xauth'))
			{
				$this->remember_me($user);
			}
			
			return TRUE;
		}
		$this->increase_login_attempts($identity);
		
		return FALSE;
	}
	
	function logout()
	{
		$session_data = array(
			'username'             => '',
			'email'                => '',
			'user_id'              => '',
			'old_last_login'       => '',
		);
		$this->session->unset_userdata($session_data);
		delete_cookie($this->remember_cookie_name);
		delete_cookie($this->identity_cookie_name);
		$this->session->sess_destroy();
		
		return true;
	}
	
	function register($email, $username, $password, $groupid = 1)
	{
		if (empty($email) || empty($username) || empty($password))
		{
			return false;
		}
		
		$email_activation = $this->email_activation;
		$ip = A();
		$salt = $this->store_salt ? $this->salt() : FALSE;
		$password = $this->hash_password($password, $salt);
		
		$data = array(
			'username'   => $username,
			'password'   => $password,
			'email'      => $email,
			'ip_address' => $ip,
			'created_on' => time(),
			'last_login' => time(),
		);
		if ($this->store_salt)
		{
			$data['salt'] = $salt;
		}
		if ($email_activation)	//需要邮件激活的
		{
			$data['active'] = 0;
			$data['activation_code'] = $activecode = sha1(md5(microtime() . random_string()));
		} else 
		{
			$data['active'] = 1;
		}
		
		$uid = $this->usersmod->commit($data);
		if ($uid > 0)
		{
			if ($email_activation)
			{
				$title = '邮件激活';
				$message = $this->email_activation;
				$url = base_url('auth/active') . '/' . $uid . '/' . $activecode;
				$message = str_replace(array('%username%', '%email%', '%url%', '%date%', ), array($username, $email, $url, date('Y-m-d'), ), $message);
				$this->myemail->send($email, $title, $message);
			}
			return $uid;
		} else 
		{
			return FALSE;
		}
	}
	
	function active($uid, $code)
	{
		$user = $this->usersmod->fetchby(array('uid'=>$uid, 'activation_code'=>$code, 'active'=>0));
		if (empty($user))
		{
			return false;
		}
		$data = array(
			'uid'				=> $uid,
			'activation_code'	=> '',
			'active'			=> 1,
		);
		$this->usersmod->commit($data);
		return true;
	}
	
	function change_password($password, $newpassword)
	{
		if (! $this->logged_in())
		{
			return false;
		}
		$user = $this->get_user();
		
		$check = $this->hash_password_db($password, $user['password'], $user['salt']);
		if ($check)
		{
			$data = array(
				'uid'				=> $user['uid'],
				'password'			=> $this->hash_password($newpassword, $user['salt']),
				'remember_code'		=> '',
			);
			
			return $this->usersmod->commit($data);
		}
		return false;
	}
	
	function forget_password($email)
	{
		if (empty($email))
		{
			return false;
		}
		$user = $this->usersmod->fetchby(array('email' => $email));
		if (empty($user))
		{
			return false;
		}
		$user = $user[0];
		$code = random_string('alnum', 16);
		$encryptCode = authcode($code, 'ENCODE');
		$this->usersmod->commit(array('uid' => $user['uid'], 'forgotten_password_code' => $encryptCode, 'forgotten_password_time' => time()));
		
		$url = base_url('auth/reset_password') . '/' . $user['uid'] . '/' . $code;
		$title = '重置密码';
		$message = CFG('*email_forgot_password', 'xauth');
		$message = str_replace(array('%email%', '%url%', '%date%', ), array($email, $url, date('Y-m-d'), ), $message);
		$this->myemail->send($email, $title, $message);
		
		return true;
	}
	
	/**
	 * 
	 * @return 
	 * 		false: 	失败
	 * 		1：		超过有效期
	 * 		true：	成功
	 */
	function reset_password($uid, $code, $password = '')
	{
		if (! $uid || empty($code))
		{
			return false;
		}
		
		$user = $this->usersmod->fetchone($uid);
		if (empty($user))
		{
			return false;
		}
		
		if ($this->forgot_password_expiration > 0 && $user['forgotten_password_time'] + $this->forgot_password_expiration < time())
		{
			return 1;
		}
		
		$encryptCode = authcode($user['forgotten_password_code']);
		if ($encryptCode != $code)
		{
			return false;
		}
		$data = array(
			'uid'						=> $user['uid'],
			'password'					=> $this->hash_password($password, $user['salt']),
			'forgotten_password_code' 	=> '', 
			'forgotten_password_time' 	=> 0,
		);
			
		$this->usersmod->commit($data);
		return true;
	}
	
	public function salt()
	{
		$raw_salt_len = 16;
	
		$buffer = '';
		$buffer_valid = false;
	
		if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
			$buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
			if ($buffer) {
				$buffer_valid = true;
			}
		}
	
		if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
			$buffer = openssl_random_pseudo_bytes($raw_salt_len);
			if ($buffer) {
				$buffer_valid = true;
			}
		}
	
		if (!$buffer_valid && @is_readable('/dev/urandom')) {
			$f = fopen('/dev/urandom', 'r');
			$read = strlen($buffer);
			while ($read < $raw_salt_len) {
				$buffer .= fread($f, $raw_salt_len - $read);
				$read = strlen($buffer);
			}
			fclose($f);
			if ($read >= $raw_salt_len) {
				$buffer_valid = true;
			}
		}
	
		if (!$buffer_valid || strlen($buffer) < $raw_salt_len) {
			$bl = strlen($buffer);
			for ($i = 0; $i < $raw_salt_len; $i++) {
				if ($i < $bl) {
					$buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
				} else {
					$buffer .= chr(mt_rand(0, 255));
				}
			}
		}
	
		$salt = $buffer;
	
		// encode string with the Base64 variant used by crypt
		$base64_digits   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
		$bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$base64_string   = base64_encode($salt);
		$salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);
	
		$salt = substr($salt, 0, $this->salt_length);
	
		return $salt;
	}
	
	public function hash_password($password, $salt = false, $use_sha1_override=FALSE)
	{
		if (empty($password))
		{
			return FALSE;
		}
	
		//bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			return $this->bcrypt->hash($password);
		}
	
		if ($this->store_salt && $salt)
		{
			return  sha1($password . $salt);
		} else
		{
			$salt = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}
	
	public function hash_password_db($password, $dbpassword, $salt = '', $use_sha1_override=FALSE)
	{
		// bcrypt
		if ($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if ($this->bcrypt->verify($password, $dbpassword))
			{
				return TRUE;
			}
	
			return FALSE;
		}
	
		// sha1
		if ($this->store_salt)
		{
			$db_password = sha1($password . $salt);
		} else
		{
			$salt1 = substr($dbpassword, 0, $this->salt_length);
	
			$db_password =  $salt1 . substr(sha1($salt1 . $password), 0, -$this->salt_length);
		}
	
		if($db_password == $dbpassword)
		{
			return TRUE;
		} else
		{
			return FALSE;
		}
	}

	public function set_session($user)
	{
		$session_data = array(
				'username'             => $user['username'],
				'email'                => $user['email'],
				'user_id'              => $user['uid'],
				'old_last_login'       => $user['last_login'],
		);
		$identityFiled = explode('|', $this->identity_field);
		if (in_array('email', $identityFiled))
		{
			$session_data['identity'] = $user['email'];
		} else
		{
			$session_data['identity'] = $user['username'];
		}
		$this->session->set_userdata($session_data);
	
		return TRUE;
	}
	
	public function update_last_login($uid)
	{
		$rows = $this->usersmod->commit(array('last_login' => time(), 'uid' => $uid));
	
		return $rows == 1;
	}
	
	function clear_login_attempts($identity)
	{
		if ($this->track_login_attempts)
		{
			$d = array(
					'ip_address'	=> A(),
					'login'			=> $identity,
			);
			$this->loginattempts->delete($d);
		}
		return false;
	}
	
	function increase_login_attempts($identity)
	{
		if ($this->track_login_attempts)
		{
			$d = array(
					'ip_address'	=> A(),
					'login'			=> $identity,
					'time'			=> time(),
			);
				
			return $this->loginattempts->commit($d);
		}
		return false;
	}
	
	function is_max_login_attempts($identity)
	{
		if ($this->track_login_attempts)
		{
			$loginattempts = $this->loginattempts->login_attempts($identity, A());
			return $loginattempts >= $this->maximum_login_attempts;
		}
		return false;
	}
	
	function remember_me($user)
	{
		if (empty($user))
		{
			return false;
		}
	
		$salt = $this->salt();
		if ($this->usersmod->commit(array('uid' => $user['uid'], 'remember_code' => $salt)))
		{
			$user_expire = CFG('*user_expire', 'xauth');
			
			$identityFiled = explode('|', $this->identity_field);
			if (in_array('email', $identityFiled))
			{
				$identity = $user['email'];
			} else
			{
				$identity = $user['username'];
			}
			
			C($this->identity_cookie_name, $identity, $user_expire);
			C($this->remember_cookie_name, $salt, $user_expire);
			
			return true;
		}
		return false;
	}
	
	function get_user()
	{
		$uid = $this->get_userid();
		if ($uid)
		{
			$user = $this->usersmod->fetchone($uid);
			return empty($user) ? NULL : $user;
		}
		return NULL;
	}
	
	function get_userid()
	{
		$user_id = $this->session->userdata('user_id');
		
		return empty($user_id) ? NULL : $user_id;
	}
	
	function get_groupid()
	{
		$user = $this->get_user();
		if (empty($user))
		{
			return false;
		}
		return $user['groupid'];
	}
	
	function get_group()
	{
		$groupid = $this->get_groupid();
		if (empty($groupid))
		{
			return false;
		}
		return $this->groupsmod->fetchone($groupid);
	}
	
	public function logged_in()
	{
		return (bool) $this->CI->session->userdata('identity');
	}
}