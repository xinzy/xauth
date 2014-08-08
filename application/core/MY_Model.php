<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('TABLE_PRE', 'x_');

class MY_Model extends CI_Model {

	public $table = '';

	public $pk_name = '';

	public $pks = array();
	
	protected $tablepre = TABLE_PRE;

	public function __construct() {
		parent::__construct();
	}

	public function set_table($table) {
		$this->table = TABLE_PRE . $table;
	}

	public function set_pk_name($pk_name) {
		$this->pk_name = $pk_name;
	}

	public function set_pks($pks) {
		$this->pks = $pks;
	}

	public function get($sql, $params = array()) {
		$lists = $this->db->query($sql, $params)->result_array();
		return isset($lists[0]) ? $lists[0] : false;
	}

	public function lists($sql, $params = array()) {
		//dump($sql);
		return $this->db->query($sql, $params)->result_array();
	}

	public function commit($data) {
		if( ! isset($data[$this->pk_name]) || (string)$data[$this->pk_name] === '' || (int)$data[$this->pk_name] === 0) {
			$this->db->insert($this->table, $data);
			$data[$this->pk_name] = $this->db->insert_id();
		}
		else {
			$this->db->where($this->pk_name, $data[$this->pk_name]);
			$this->db->update($this->table, $data);
		}
		return $data[$this->pk_name];
	}

	public function mu_commit($data) {
		foreach ($this->pks as $pk) {
			$this->db->where($pk, $data[$pk]);
		}
		if ($this->db->count_all_results($this->table)) {
			foreach ($this->pks as $pk) {
				$this->db->where($pk, $data[$pk]);
			}
			$this->db->update($this->table, $data);
		}
		else {
			$this->db->insert($this->table, $data);
		}
		return;
	}

	public function count_all($where = '') {
		$where = isset($where) && !empty($where) ? $where : '1 = 1';
		$sql = "SELECT count(*) c FROM {$this->table} WHERE $where ";
		$res = $this->db->query($sql)->result_array();
		return $res[0]['c'];
	}

	/**
	 * Delete rows from table related to by $name
	 *
	 * @access public
	 * @param string $name Table Name
	 * @param mixed $where Rows to delete
	 * @return Query Object
	 */
	public function delete($where) {
		$this->db->where($where);
		return $this->db->delete($this->table);
	}

	/**
	 * Get fields
	 */
	public function table_fields() {
		return $this->db->list_fields($this->table);
	}
	
	function fetchby($param)
	{
		return $this->db->get_where($this->table, $param)->result_array();
	}
	
	function fetchone($id)
	{
		$sql = "SELECT * FROM `{$this->table}` WHERE `{$this->pk_name}` = ?";
		return $this->get($sql, array($id));
	}

	function deleteone($id)
	{
		$where = array($this->pk_name => $id);
		$this->delete($where);
	}
}

/* End of file MY_Model.php */
/* Location: ./application/models/MY_Model.php */