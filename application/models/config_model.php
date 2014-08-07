<?php

class Config_model extends MY_Model
{
	function __construct()
	{
		parent::__construct();
		
		$this->set_table('config');
		$this->pk_name = 'name';
	}
	
	function getall()
	{
		$sql = "SELECT * FROM {$this->table}";
		$data = parent::lists($sql);
		$result = array();
		
		foreach ($data as $k => $v)
		{
			$result[$v['name']] = $v['value'];
		}
		
		return $result;
	}
	
	function save($configs)
	{
		foreach ($configs as $k => $v)
		{
			$sql = "REPLACE {$this->table} (`name`, `value`) VALUES ('$k', '$v')";
			$this->db->query($sql);
		}
	}
	
	function resetarticlemonth($time)
	{
		$cfg = array(
			'articleday'	=> $time,
			'articleweek'	=> $time,
			'articlemonth'	=> $time,
		);
		$this->save($cfg);
	}
	
	function resetarticleweek($time)
	{
		$cfg = array(
			'articleday'	=> $time,
			'articleweek'	=> $time,
		);
		$this->save($cfg);
	}
	
	function resetarticleday($time)
	{
		$cfg = array(
			'articleday'	=> $time,
		);
		$this->save($cfg);
	}
}