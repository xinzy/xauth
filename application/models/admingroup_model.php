<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admingroup_model extends MY_model
{
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('admingroup');
		$this->set_pk_name('gid');
	}
	
	function find_by_id($groupid)
	{
		return parent::get("SELECT * FROM {$this->table} wHERE {$this->pk_name} = ?", array($groupid));
	}
	
	function lists_all($start = 0, $limit = 20, $where='')
	{
		$where = empty($where) ? '' : 'WHERE '.$where;
		$sql = "SELECT * FROM {$this->table} {$where} ORDER BY {$this->pk_name} ASC "
				 . ($limit == 0 ? '' : " LIMIT $start, $limit");
		
		return parent::lists($sql);
	}
	
	function check_usergroup_id($id)
	{
		return parent::count_all($this->table,'gid='.$id);
	}
	
	function is_only($name,$id)
	{
		return parent::get("SELECT * FROM {$this->table} WHERE `groupname` = ? AND gid <> ?",array($name,$id));
	}
	function check_in_members($id)
	{
		$result = parent::get("SELECT COUNT(*) as c FROM `members` WHERE `groupid` = $id");
		return $result['c'];
	}
}
