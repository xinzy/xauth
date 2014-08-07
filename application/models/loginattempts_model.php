<?php

class Loginattempts_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('loginattempts');
		$this->set_pk_name('id');
	}
	
	function login_attempts($identity, $ipaddress = '', $expire = 600)
	{
		$time = time() - $expire;
		$where = "login = '{$identity}' AND `time` > $time ";
		if ($ipaddress)
		{
			$where .= " AND ip_address = '{$ipaddress}' ";
		}
		return parent::count_all($where);
	}
}