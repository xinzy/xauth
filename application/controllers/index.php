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
		$this->load->view('www/index', $tp);
	}	
}