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
		V('password', '密码', 'required');
		V('newpass', '新密码', 'required|min_length[4]|max_length[16]');
		V('confirm', '确认密码', 'required|matches[newpass]|min_length[4]|max_length[16]');
		
		if (V() === FALSE)
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
		$username = isset($_GET['username']) ? $_GET['username'] : '';
		$this->template['username'] = $username;
		
		$usergroup = $this->admingroupmod->lists_all(0, 0);
		$temp = array();
		foreach ($usergroup as $v)
		{
			$temp[$v['gid']] = $v;
		}
		$usergroup = $temp;
		unset($temp);
		$this->template['usergroup'] = $usergroup;
		
		$groupid = U(4);
		if (empty($groupid) || !is_numeric($groupid))
		{
			$groupid = 0;
		}
		$this->template['currgroupid'] = $groupid;
		$start = U(5);
		if (empty($start) || !is_numeric($start))
		{
			$start = 1;
		}
		
		if ($username == '')
		{
			$this->pageconf['uri_segment'] = 5;
			$this->pageconf['base_url'] = $this->template['base_url'] . 'admin/system/member/'.$groupid;
			$this->pageconf['total_rows'] = $this->adminmod->count_user($groupid);
			PG($this->pageconf);
			$this->template['pagination'] = PG();
		}
		
		$this->template['users'] = $this->adminmod->lists_user($username, $groupid, $start, $username == '' ? $this->pageconf['per_page'] : 1000);
		
		$this->output();
	}
	
	function addadmin()
	{
		V('username', '登录名', 'required|min_length[4]|max_length[16]|is_unique[admin.username]');
		V('email', 'Email', 'required|valid_email|is_unique[admin.email]');
		V('password', '密码', 'required|min_length[6]|max_length[16]');
		V('groupid', '管理员组', 'required|integer');
		
		if (V() === true)
		{
			$this->userauth->createadmin(set_value('username'), set_value('email'), set_value('password'), set_value('groupid'));
			$this->message('index/admins', '添加管理员完毕', 'success');
		} else 
		{
			$this->template['usergroup'] = $this->admingroupmod->lists_all(0, 0);
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