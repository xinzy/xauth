<?php
class Index extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function index()
	{
		$tp['islogin'] = $this->islogin();
		$tp['user'] = $this->getuser();
		$tp['group'] = $this->xauth->get_group();
		$this->load->view('www/index', $tp);
	}	
}