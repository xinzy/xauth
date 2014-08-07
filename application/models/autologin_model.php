<?php

class Autologin_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('autologin');
		$this->pk_name = 'keys';
	}
	
	function delete_by_uid($uid)
	{
		$where = array('uid' => $uid);
		parent::delete($where);
	}
}