<?php

class Users_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('users');
		$this->set_pk_name('uid');
	}
	
	
}