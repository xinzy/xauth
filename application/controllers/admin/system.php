<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System extends MY_admin
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function index()
	{
		redirect(base_url() . 'admin/system/member');
	}
	
	/**
	 * 用户
	 */
	function member()
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
		
		$this->output('system/member');
	}
	
	function memberdetail()
	{
		$uid = $this->uri->segment(4);
		if (empty($uid) || ! is_numeric($uid))
		{
			$this->message('system/member', '参数错误','error');
			return ;
		}
		$user = $this->adminmod->find_by_id($uid);
		if (empty($user))
		{
			$this->message('system/member', '没有该用户','error');
			return ;
		}
		
		$this->template['user'] = $user;
		$this->output('system/show');
	}
	
	function addmember()
	{
		$data = P('user', true);
			
		if (empty($data))
		{
			$this->template['usergroup'] = $this->admingroupmod->lists_all(0, 0);
			$this->output('system/addmember');
		} else 
		{
			if (empty($data['username']))
			{
				$this->message('system/addmember', '用户名不能为空', 'error');
			} else if (empty($data['password']))
			{
				$this->message('system/addmember', '密码不能为空', 'error');
			} else 
			{
				$user = $this->adminmod->check_username($data['username']);
				if (! empty($user))
				{
					$this->message('system/addmember', '用户名已存在', 'error');
				} else
				{
					$data['salt'] = random_string('alnum', 8);
					$data['password'] = md5(md5($data['password']) . $data['salt']);
					
					$uid = $this->adminmod->commit($data);
					if ($uid > 0)
					{
						$this->message('system/member', '添加成功', 'success');
					} else 
					{
						$this->message('system/addmember', '操作失败', 'error');
					}
				}
			}
		}
	}
	
	function modifymember()
	{
		$userid = U(4);
		if (empty($userid) || ! is_numeric($userid))
		{
			$user = P('user', true);
			if (empty($user))
			{
				$this->message('system/member', '参数错误', 'error');
			} else 
			{
				if ($user['password'] != '')
				{
					if (strlen($user['password']) < 6)
					{
						$this->message('system/member/', '密码长度至少6位', 'error');
						return ;
					} else 
					{
						$user['salt'] = random_string('alnum', 8);
						$user['password'] = md5(md5($user['password']) . $user['salt']);
					}
				} else 
				{
					unset($user['password']);
				}
				$uid = $this->adminmod->commit($user);
				if ($uid > 0)
				{
					$this->message('system/member', '操作成功', 'success');
				} else 
				{
					$this->message('system/member', '修改失败', 'error');
				}
			}
		} else 
		{
			$user = $this->adminmod->find_by_id($userid);
			if (empty($user))
			{
				$this->message('system/member', '用户不存在', 'error');
			} else 
			{
				if ($this->admin['groupid'] != 1 && $user['groupid'] == 1)
				{
					$this->message('system/member', '您没有权限修改管理员信息', 'error');
				} else 
				{
					$this->template['usergroup'] = $this->admingroupmod->lists_all(0, 0);
					$this->template['user'] = $user;
					$this->output('system/modifymember');
				}
			}
		}
	}
	
	function deletemember()
	{
		$userid = U(4);
		if (empty($userid) || ! is_numeric($userid))
		{
			$this->message('system/member', '参数错误', 'error');
		} else 
		{
			$user = $this->adminmod->find_by_id($userid);
			if (empty($user))
			{
				$this->message('system/member', '用户不存在', 'error');
			} else if ($userid == $this->adminuser['mid'])
			{
				$this->message('system/member', '不能删除自己', 'error');
			} else 
			{
				if ($this->adminuser['groupid'] != 1 && $user['groupid'] == 1)
				{
					$this->message('system/member', '您没有权限删除管理员', 'error');
				} else 
				{
					$this->adminmod->delete_by_id($userid);
					
					$this->message('system/member', '操作成功', 'error');
				}
			}
		}
	}
	
	/**
	 * 用户组
	 */
	function usergroup()
	{
		$this->template['usergroups'] = $this->admingroupmod->lists_all(0, 20);
		
		$this->output('system/usergroup');
	}
	
	function add_usergroup()
	{
		if(!empty($_POST))
		{
			V('name','用户组名称','trim|required|xss_clean|is_unique[admingroup.groupname]');
			if($this->form_validation->run()!== FALSE)
			{
				if($this->admingroupmod->commit(array("groupname"=>set_value('name'))))
				{
					$this->message('system/usergroup', '添加成功','success');
					return;
				}	
				else
				{
					$this->output('system/add_usergroup');
				}
			}
			else
			{
				$this->output('system/add_usergroup');
			}
		}
		else
		{
			$this->output('system/add_usergroup');
		}
	}
	function edit_usergroup()
	{
		$gid = (int)$this->uri->segment(4);
		if($this->_check_usergroup_id($gid) > 0)
		{
			$this->template['data'] = $this->admingroupmod->find_by_id($gid);
			if(!empty($_POST))
			{
				V('name','用户组名称','trim|required|xss_clean|callback_is_only_groupname');
				if(Vrun()!== FALSE)
				{
					if($this->admingroupmod->commit(array("groupname"=>set_value('name'),"gid"=>$gid)))
					{
						$this->message('system/usergroup', '添加成功','success');
						return;
					}	
					else
					{
						$this->output('system/edit_usergroup');
					}
				}
				else
				{
					$this->output('system/edit_usergroup');
				}
			}
			else
			{
				$this->output('system/edit_usergroup');
			}
		}
		else
		{
			$this->message('system/usergroup', '没有找到该用户组','error');
		}
	}
	function delete_usergroup()
	{
		$id = (int)U(4);
		$back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url()."admin/system/usergroup";
		if($id==1)
		{
			$this->message('system/usergroup', '没有权限删除系统管理员','error');
		}
		elseif($this->_check_usergroup_id($id)==0)
		{
			$this->message('system/usergroup', '不存在该用户组','error');
		}
		elseif($this->admingroupmod->check_in_members($id) > 0)
		{
			$this->message($back_url, '有用户在该组中，不能删除','error');
		}
		elseif($this->admingroupmod->delete(array("gid"=>$id)))
		{
			$this->message($back_url, '删除成功','success');
			return;
		}
		else
		{
			$this->message($back_url, '删除失败','error');
		}
	}
	
	function _check_usergroup_id($id)
	{
		return $this->admingroupmod->check_usergroup_id($id);
	}
	
	function is_only_groupname($name)
	{
		$id = (int)$this->uri->segment(4);
		if($this->admingroupmod->is_only($name,$id))
		{
			$this->form_validation->set_message("is_only_groupname","用户组名称重复");
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function userpms()
	{
		$groupid = U(4);
		if (! is_numeric($groupid))
		{
			$this->message('system/usergroup', '参数错误', 'error');
		} else 
		{
			$usergroup = $this->admingroupmod->find_by_id($groupid);
			if (empty($usergroup))
			{
				$this->message('system/usergroup', '没有该用户组', 'error');
			} else 
			{
				$usergroup['permissions'] = explode(',',  $usergroup['permission']);
				$this->template['usergroup'] = $usergroup;
				$this->template['permissions'] = $this->permission_mod->list_tree();
				
				$this->output('system/userpms');
			}
		}
	}
	
	/**
	 * 用户组权限
	 */
	function permission()
	{
		$groupid = U(4);
		if (! is_numeric($groupid))			// 全部用户权限
		{
			$userpms = P('userpms', true);
			if (empty($userpms))			// 
			{
				$usergroups = $this->admingroupmod->lists_all(0, 20, 'gid<>1');
				//print_r($usergroups);die;
				$tmp = array();
				foreach ($usergroups as $k => $v)
				{
					$tmp[$v['gid']]['groupname'] = $v['groupname'];
					$tmp[$v['gid']]['permissions'] = explode(',', $v['permission']);
				}
				unset($usergroups);
				$this->template['usergroups'] = $tmp;
				$this->template['permissions'] = $this->permission_mod->list_tree();
				
				$this->output('system/permission');
			} else 							// 这里是修改后的提交处理
			{
			//	var_export($userpms);
				foreach ($userpms as $iptgid => $iptupms)
				{
					if (is_numeric($iptgid))
					{
						$temp = array();
						foreach ($iptupms as $kk => $vv)
						{
							if (is_numeric($vv))
							{
								$temp[] = $vv;
							}
						}
						if (! empty($temp))
						{
							$data = array(
								'gid'			=> $iptgid,
								'permission'	=> implode(',', $temp),
							);
							//print_r($data);die;
							$this->admingroupmod->commit($data);
							
							// 以下2行 刷新权限文件
							$this->permission->set_groupid($iptgid);
							$this->permission->flush();
						}
					}
				}
				$this->message('system/permission', '修改成功', 'success');
			}
		} else 			// 指定id用户组权限
		{
			$usergroup = $this->admingroupmod->find_by_id($groupid);
			if (empty($usergroup))
			{
				$this->message('system/usergroup', '没有该用户组', 'error');
			} else 
			{
				$usergroup['permissions'] = explode(',',  $usergroup['permission']);
				$this->template['usergroup'] = $usergroup;
				$this->template['permissions'] = $this->permission_mod->list_tree();
				
				$this->output('system/userpms');
			}
		}
	}
}
