<?php

class Auth extends MY_Controller
{
	
	private $min_password_length;
	private $max_password_length;
	private $min_username_length;
	private $max_username_length;
	
	private $identityField;
	private $email_activation;

	function __construct()
	{
		parent::__construct();

		$this->load->config('xauth', true);
		$this->min_password_length = $this->config->item('min_password_length', 'xauth');
		$this->max_password_length = $this->config->item('max_password_length', 'xauth');
		$this->min_username_length = $this->config->item('min_username_length', 'xauth');
		$this->max_username_length = $this->config->item('max_username_length', 'xauth');
		
		$this->identityField = $this->config->item('identity', 'xauth');
		$this->email_activation = $this->config->item('email_activation', 'xauth');
		
		header('Content-type: text/html; charset=utf-8');
	}
	
	function index()
	{
		if ($this->islogin())
		{
			redirect('');
		} else 
		{
			redirect('auth/login');
		}
	}
	
	function login()
	{
		if ($this->islogin())
		{
			redirect('');
		}
		V('identity', 'Identity', 'required');
		V('password', 'Password', "required|min_length[{$this->min_password_length}]|max_length[{$this->max_password_length}]");
		
		$tp = array('message' => '');
		
		if (V() == true)
		{
			$identity = set_value('identity');
			$password = set_value('password');
			$remember = P('remember');
			
			$res = $this->xauth->login($identity, $password, $remember);
			if ($res === true)
			{
				echo '登陆成功';
				redirect();
			} else if ($res === false)
			{
				$tp['message'] = '用户名或密码错误';
			} else if ($res === 1)
			{
				$tp['message'] = '账号未激活';
			} else if ($res === 2)
			{
				$tp['message'] = '超过最大尝试次数';
			}
		}
		$this->load->view('www/auth/login', $tp);
	}
	
	function register()
	{
		if ($this->islogin())
		{
			redirect('');
		}
		
		V('username', 'Username', "required|is_unique[users.username]|min_length[{$this->min_username_length}]|max_length[{$this->max_username_length}]");
		V('email', 'Email', "required|is_unique[users.username]|valid_email");
		V('password', 'Password', "required|min_length[{$this->min_password_length}]|max_length[{$this->max_password_length}]");
		V('groupid', 'Groupid', "required|greater_than[0]|less_than[3]");
		
		if (V() == TRUE)
		{
			$username = set_value('username');
			$email = set_value('email');
			$password = set_value('password');
			$groupid = set_value('groupid');
			
			$uid = $this->xauth->register($email, $username, $password, $groupid);
			if ($uid > 0)
			{
				if ($this->email_activation)
				{
					echo '注册成功，请登录邮箱验证';
				} else
				{
					redirect();
				}
			} else 
			{
				exit('注册失败');
			}
		} else 
		{
			$tp['groups'] = $this->groupsmod->lists_all();
			$this->load->view('www/auth/register', $tp);
		}
	}
	
	function logout()
	{
		$this->xauth->logout();
		redirect();
	}
	
	function cgpasswd()
	{
		if (! $this->islogin())
		{
			redirect('auth/login');
		}
		$tp = array('message'=>'');
		V('password', 'Password', "required|min_length[{$this->min_password_length}]|max_length[{$this->max_password_length}]");
		V('newpasswd', 'New Password', "required|min_length[{$this->min_password_length}]|max_length[{$this->max_password_length}]");
		V('confirm', 'Confirm', "required|min_length[{$this->min_password_length}]|max_length[{$this->max_password_length}]|matches[newpasswd]");
		
		if (V())
		{
			$password = set_value('password');
			$newpasswd = set_value('newpasswd');
			
			$ret = $this->xauth->change_password($password, $newpasswd);
			if ($ret)
			{
				$this->xauth->logout();
				echo '修改成功，请重新登录';
				redirect();
			} else 
			{
				$tp['message'] = '修改失败';
			}
		} 
		$this->load->view('www/auth/cgpassword', $tp);
	}
	
	function forget_password()
	{
		if ($this->islogin())
		{
			redirect();
		}
		$tp = array('message'=>'');
		V('email', 'Email', 'required|valid_email');
		
		if (V())
		{
			$email = set_value('email');
			
			$ret = $this->xauth->forget_password($email);
			if ($ret)
			{
				echo '申请成功，请登录邮箱激活';
			} else 
			{
				$tp['message'] = '该邮箱没有注册或申请失败';
			}
		}
		$this->load->view('www/auth/forget_password', $tp);
	}
	
	function reset_password()
	{
		$uid = intval(U(3));
		$code = U(4);

		V('password', 'Password', "required|min_length[{$this->min_password_length}]|max_length[{$this->max_password_length}]");
		V('confirm', 'Confirm', "required|min_length[{$this->min_password_length}]|max_length[{$this->max_password_length}]|matches[password]");

		$tp = array('message'=>'');
		if (V())
		{
			$password = set_value('password');
			$ret = $this->xauth->reset_password($uid, $code, $password);
			
			if ($ret === false)
			{
				$tp['message'] = '重置失败失败';
			} else if ($ret === 1)
			{
				$tp['message'] = '超过有效期';
			} else if ($ret === true)
			{
				echo 'success';
				redirect('auth/login');
			}
		}
		$this->load->view('www/auth/reset_password');
	}
	
	function active()
	{
		$uid = U(3);
		$code = U(4);
		
		$ret = $this->xauth->active($uid, $code);
		if ($ret)
		{
			echo '激活成功';
		} else 
		{
			echo '激活错误';
		}
	}
}
