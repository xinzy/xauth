<?php

class Page_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('page');
		$this->pk_name = 'id';
	}
	
	function lists_all()
	{
		$sql = "SELECT * FROM {$this->table} ORDER BY id ASC";
		
		return parent::lists($sql);
	}
	
	function find_by_unique($unique)
	{
		$sql = "SELECT * FROM {$this->table} WHERE `unique` = '$unique'";
		
		return parent::get($sql);
	}
}