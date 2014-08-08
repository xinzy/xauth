<?php

class Groups_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();

		$this->set_table('groups');
		$this->set_pk_name('groupid');
	}
	
	function lists_all()
	{
		$sql = "SELECT * FROM {$this->table}";
		
		return parent::lists($sql);
	}
	
}
