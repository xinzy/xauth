<?php

class Page extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function index()
	{
		$unique = $this->uri->segment(2);
		if (! empty($unique))
		{
			$page = $this->template['page'] = $this->pagemod->find_by_unique($unique);
			if (! empty($page))
			{
				$this->output('page/index');
				return ;
			}
		}
		$this->messages('', 'fail', '您访问的页面不存在');
	}
}
