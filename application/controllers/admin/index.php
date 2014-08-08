<?php

class Index extends MY_admin
{

	function __construct()
	{
		parent::__construct();
		
	}
	
	function index()
	{
		$this->output('index/index');
	}

	function modifypass()
	{
		$this->form_validation->set_rules('password', '密码', 'required');
		$this->form_validation->set_rules('newpass', '新密码', 'required|min_length[4]|max_length[16]');
		$this->form_validation->set_rules('confirm', '确认密码', 'required|matches[newpass]|min_length[4]|max_length[16]');
		
		if ($this->form_validation->run() === FALSE)
		{
			$this->output('index/modifypass');
		} else 
		{
			$user = $this->adminmod->auth($this->admin['username'], set_value('password'));
			if (empty($user))
			{
				$this->message('index/modifypass', '密码不正确', 'fail');
			} else 
			{
				$this->adminmod->modifypass($this->admin['uid'], set_value('newpass'));
				$this->userauth->logout();
				$this->message('index', '修改密码成功，请使用新密码重新登录');
			}
		}
	}
	
	function noperm()
	{
		$this->message('index', '您还没有权限访问该资源');
	}
	
	function admins()
	{
		$page = U(4);
		intval($page) == 0 && $page = 1;
		
		$this->pageconf['per_page'] = 1;
		$this->pageconf['uri_segment'] = 4;
		$this->pageconf['total_rows'] = $this->adminmod->count_all();
		$this->pageconf['base_url'] = base_url('admin/index/admins');
		PG($this->pageconf);
		
		$this->template['admins'] = $this->adminmod->lists_all($page, $this->pageconf['per_page']);
		$this->template['pages'] = PG();
		$this->output();
	}
	
	function addadmin()
	{
		V('username', '登录名', 'required|min_length[4]|max_length[16]|is_unique[admin.username]');
		V('email', 'Email', 'required|valid_email|is_unique[admin.email]');
		V('password', '密码', 'required|min_length[6]|max_length[16]');
		V('admintype', '管理员类型', 'required');
		
		if (V() === true)
		{
			$this->userauth->createadmin(set_value('username'), set_value('email'), set_value('password'), set_value('admintype'));
			$this->message('index/admins', '添加管理员完毕', 'success');
		} else 
		{
			$this->output();
		}
	}
	
	function edit()
	{
		$adminid = U(4);
		$admin = $this->adminmod->fetchone($adminid);
		if (empty($admin))
		{
			$this->message('index/admins', '查无此人', 'fail');
		}
		
		V('email', 'Email', 'valid_email|is_unique[admin.email]');
		V('password', '密码', 'min_length[6]|max_length[16]');
		
		if (V() === true)
		{
			$email = set_value('email');
			$password = set_value('password');
			
			if (empty($email) && empty($password))
			{
				$this->message('index/admins', '没有什么可以修改的', 'fail');
			}
			
			$data = array(
				'uid'		=> $adminid,
			);
			
			if (! empty($email))
			{
				$data['email'] = $email;
			}
			if (! empty($password))
			{
				$data['salt'] = random_string('alnum', 12);
				$data['password'] = md5(md5($password) . $data['salt']);
			}
			$this->adminmod->commit($data);
			$this->message('index/admins');
		} else 
		{
			$this->output();
		}
	}
	
	function delete($id = 0)
	{
		$this->adminmod->deleteone($id);
		$this->message('index/admins', '删除成功', 'success');
	}
}