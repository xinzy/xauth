<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Controller Class
 *
 * Extends CodeIgniter Controller
 * Basic Model loads and settings set.
 */

class MY_Controller extends CI_Controller
{
	
	protected $admin = NULL;

	public $template = array();
	public $pageconf = array();
	
	protected $siteconfig = array();

	protected $loadmodels = array();
	
	public function __construct()
	{
		parent::__construct();

		date_default_timezone_set('Asia/Shanghai');
		//分页配置
		$this->pageconf['per_page'] = 6;
		$this->pageconf['num_links'] = 3;
		$this->pageconf['use_page_numbers'] = TRUE;
		$this->pageconf['page_query_string'] = FALSE;
		$this->pageconf['first_link'] = '首页';
		$this->pageconf['last_link'] = '尾页';
		$this->pageconf['next_link'] = '下一页';
		$this->pageconf['prev_link'] = '上一页';

		$this->_load_model();
		
		$this->template['siteconfig'] = $this->siteconfig = $this->configmod->getall();
		$this->form_validation->set_error_delimiters('<span style="color: #F00;">', '</span>');
		$this->template['base_url'] = base_url();
		$this->template['title'] = $this->siteconfig['site_title'];
		$this->template['site_keywords'] = $this->siteconfig['site_keywords'];
		$this->template['site_description'] = $this->siteconfig['site_description'];
		
		$this->admin = $this->userauth->checklogin();
		$this->template['isadmin'] = 0;
		if ($this->isadmin())
		{
			$this->template['isadmin'] = 1;
			$this->template['admin'] = $this->admin;
		}
	}
	
	function _load_model()
	{
		$this->load->config('loadmodels');
		$this->loadmodels = config_item('loadmodels');
		
		foreach ($this->loadmodels as $key => $val)
		{
			if (is_int($key))
			{
				$name = $val . 'mod';
			} else 
			{
				if (strpos($key, 'mod') !== false)
				{
					$name = $key;
				} else 
				{
					$name = $key . 'mod';
				}
			}
			$this->load->model($val . '_model', $name);
		}
	}
	
	protected function islogin()
	{
		return $this->xauth->logged_in();
	}
	
	protected function isadmin()
	{
		return ! empty($this->admin) && $this->admin['uid'] > 0;
	}
	
	protected function getuserid()
	{
		return $this->xauth->get_user_id();
	}
	
	protected function getuser()
	{
		return $this->xauth->get_user();
	}
	
	protected function settitle($title)
	{
		$this->template['title'] = $title . ' -- ' . $this->template['title'];
	}
	
	protected function show_messages($url='', $type='success', $messages='', $time = 3)
	{
		$get_array = array();
		if (strstr($url, 'http') === FALSE)
		{
			$url = base_url() . $url;
		}
		$get_array['url'] = $url;
		if (empty($get_array['url']))
		{
			$get_array['url'] = site_url();
		}

		$this->template['time'] = $time;
		$this->template['get_array'] = $get_array;
		$this->template['type'] = $type;
		$this->template['messages'] = $messages;

		$html = $this->load->view('www/messages', $this->template, TRUE);
		echo $html;
		exit();
	}
}

define('ENABLE_PERMISSION', 0);

class MY_admin extends MY_Controller
{
	protected $IS_POST;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_check_login();
		
		$this->settitle('管理中心');
		$this->IS_POST=$this->input->post()?TRUE:FALSE;
		$this->form_validation->set_error_delimiters('<span style="color: #F00;">', '</span>');
		$this->pageconf['per_page'] = 30;
		$this->pageconf['num_links'] = 5;
		$this->pageconf['uri_segment'] = 4;
		
		if (ENABLE_PERMISSION)
		{
			$this->load->library('permission');
			$this->permission->set_groupid($this->admin['groupid']);
			$this->permissions = $this->permission->get_permissions();
			$this->permission_arr = $this->permission->get_permission_array();
			//print_r($this->permission_arr);die;
			$this->check_permission();
		}
	}

	/**
	 * 检查用户登录
	 */
	function _check_login()
	{
		if ($this->isadmin())
		{
			$lastactive = intval(get_cookie(LAST_ACTIVE));
	
			if ($lastactive === FALSE)
			{
				redirect('admin/logging');
			} else if (time() - $lastactive > 1500 * 60)
			{
				$this->message('logging', '您长时间没有操作，已超时，请重新登录', 'fail');
			} else
			{
				set_cookie(LAST_ACTIVE, time(), 0);
			}
		} else
		{
			redirect('admin/logging');
		}
	}
	
	public function output($view = FALSE)
	{
		$controller = $this->router->fetch_class();
		$action = $this->router->fetch_method();
		if (empty($view))
		{
			$view = $controller . '/' . $action;
		}
		
		$this->load->view('admin/public/header', $this->template);
		$this->load->view('admin/public/left');
		$this->load->view('admin/'.$view);
		$this->load->view('admin/public/footer');
	}
	
	function message($url = '',$messages = '',$type = 'success')
	{
		$get_array = array();
		$url = strpos($url,'http') === false ? base_url().'admin/'.$url : $url;
		$get_array['url'] = $url;
		if (empty($get_array['url']))
		{
			$get_array['url'] = base_url()."admin/home";
		}
		$this->template['get_array'] = $get_array;
		$this->template['type'] = $type;
		$this->template['messages'] = $messages;
		$msg = $this->load->view('admin/public/header',$this->template,TRUE);
		$msg .= $this->load->view('admin/public/left',$this->template,TRUE);
		$msg .= $this->load->view('admin/messages',$this->template,TRUE);
		$msg .= $this->load->view('admin/public/footer',$this->template,TRUE);
		echo $msg;
		exit;
	}

	function check_permission()
	{
		if ($this->admin['groupid'] == 1)
		{
			return true;
		}
		
		$controller = U(2);
		$action = U(3);
		! $controller && $controller = 'index';
		! $action && $action = 'index';
	
		if(!$this->_check_permission($controller, $action))
		{
			redirect('admin/index/noperm');
		}
	}
	
	/**
	 * 检查url权限
	 */
	public function _check_permission($c, $a)
	{
		if ($c == 'index')
		{
			return TRUE;
		}
	
		if(in_array($a.'@'.$c, $this->permissions))
		{
			return TRUE;
		} else
		{
			return FALSE;
		}
	}
}
