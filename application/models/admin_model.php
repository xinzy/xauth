<?php

class Admin_model extends MY_Model
{
	
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('admin');
		$this->pk_name = 'uid';
	}
	
	function find_by_username($username)
	{
		$sql = "SELECT * FROM {$this->table} WHERE username = ?";
		
		return parent::get($sql, array('username' => $username));
	}
	
	function find_by_email($email)
	{
		$sql = "SELECT * FROM {$this->table} WHERE email = ?";
		
		return parent::get($sql, array('email' => $email));
	}
	
	function auth($username, $password)
	{
		$sql = "SELECT * FROM {$this->table} WHERE username = ? AND `password` = MD5(CONCAT(MD5('$password'), salt))";
		$user = parent::get($sql, array('username' => $username));
		if (! empty($user))
		{
			$user['password'] = '';
			return $user;
		}
		return FALSE;
	}
	
	function modifypass($uid, $password)
	{
		$sql = "UPDATE {$this->table} SET `password` = MD5(CONCAT(MD5('$password'), salt)) WHERE {$this->pk_name} = '$uid'";
		$this->db->query($sql);
	}
	
	function lists_all($param, $page = 1, $limit = 20)
	{
		$offset = ($page - 1) * $limit;
		
		$sql = "SELECT * 
					FROM {$this->table} 
					WHERE 1 = 1
					ORDER BY {$this->pk_name} ASC
					LIMIT $offset, $limit ";
		
		return parent::lists($sql);
	}
	
}