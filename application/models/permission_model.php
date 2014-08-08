<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission_model extends MY_model
{
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('permission');
		$this->set_pk_name('perid');
	}
	
	/**
	 * 通过多个id来查询
	 * @param $ids
	 * 		id用逗号隔开的串
	 */
	function list_by_ids($ids)
	{
		if (empty($ids))
		{
			return array();
		}
		return parent::lists("SELECT * FROM {$this->table} WHERE {$this->pk_name} IN ($ids)");
	}
	
	function list_all()
	{
		$sql = "SELECT * FROM {$this->table} ";
		
		return parent::lists($sql);
	}
	
	function list_tree()
	{
		$permissions = $this->list_all();
		$data = array();
		
		foreach ($permissions as $k => $v)
		{
			$data[$v['controller']][] = $v;
		}
		return $data;
	}
}
