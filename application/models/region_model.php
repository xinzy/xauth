<?php

class Region_model extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('region');
		$this->set_pk_name('id');
	}
	
	function lists_by_pid($pid = 0)
	{
		$sql = "SELECT * 
					FROM {$this->table}
					WHERE pid = '{$pid}' ";
		
		return parent::lists($sql);
	}
	
	function fetchAddr($id)
	{
		$item = $this->fetchone($id);
		if (empty($item))
		{
			return NULL;
		}
		$sql = "SELECT * 
					FROM {$this->table} 
					WHERE {$this->pk_name} IN ({$item['path']})
					ORDER BY find_in_set({$this->pk_name},'{$item['path']}')";
		return parent::lists($sql);
	}
}