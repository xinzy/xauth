<?php
if(!defined('BASEPATH')) die('No direct script access allowed!');

class Permission
{
	private $groupid; //当前用户组id
	
	private $cache_dir;	//缓存目录
	private $cache_file_pre; //缓存文件前缀
	private $cache_file_ext; //缓存文件扩展名
	
	private $cache_arr_pre;
	private $cache_arr_ext;
	
	private $instance;		//CI实例
	
	function __construct()
	{
		$this->cache_dir = FCPATH . 'application/cache/group/';
		
		$this->cache_file_pre = 'permission_';
		$this->cache_file_ext = '.php';
		
		$this->cache_arr_pre = 'permission_arr_';
		$this->cache_arr_ext = '.php';
		
		$this->instance = & get_instance();
		$this->instance->load->model('admingroup_model', 'group_mod');
		$this->instance->load->model('permission_model', 'permission_mod');
	}
	
	function set_groupid($groupid)
	{
		$this->groupid = $groupid;
	}
	
	/**
	 * 通过管理员groupid获取权限
	 */
	function get_permissions()
	{
		$cache_file = $this->cache_dir . $this->cache_file_pre . $this->groupid . $this->cache_file_ext;
        //echo $cache_file;die;
		if (! file_exists($cache_file))	//如果缓存文件不存在，先刷新创建
		{
			$this->flush();
		}

		if (file_exists($cache_file))
		{
			$arr = include $cache_file;
			return $arr['permission'];
		} else 
		{
			return array();
		}
	}
	
	function get_permission_array()
	{
		$cache_file = $this->cache_dir . $this->cache_arr_pre . $this->groupid . $this->cache_arr_ext;
        //echo $cache_file;die;
		if (! file_exists($cache_file))	//如果缓存文件不存在，先刷新创建
		{
			$this->flush();
		}

		if (file_exists($cache_file))
		{
			$arr = include $cache_file;
			return $arr;
		} else 
		{
			return array();
		}
	}
	
	/**
	 * 刷新缓存文件
	 */
	function flush()
	{
		if ($this->groupid > 0)
		{
			$group = $this->instance->group_mod->find_by_id($this->groupid);
			//print_r($group);die;
			if (! empty($group))
			{
                if($this->groupid == 1) 
                {
                    $this->_write_cache($group);
                } else 
                {
                    $permission = $this->instance->permission_mod->list_by_ids($group['permission']);
                   // var_export($permission); die;
                    $this->_write_cache($group, $permission);
                }
			}
		}
	}
	
	/**
	 * 输入到缓存文件
	 * @param $groupid
	 * @param $p
	 */
	function _write_cache($group, $p = array())
	{
		if (! $this->_writeable($this->cache_dir))
		{
			exit('缓存目录不可写');
		}

		$group['permission'] = array();
        $temp = array();

		foreach ($p as $key => $val)
		{
            $group['permission'][] = $val['action'].'@'.$val['controller'];
            $temp[$val['controller']][] = $val['action'];
		}
		$cache_file = $this->cache_dir . $this->cache_file_pre . $this->groupid . $this->cache_file_ext;
		
		$file = fopen($cache_file, 'w');
		fwrite($file, "<?php \n\r return " . var_export($group, TRUE) . ';');
		fflush($file);
		fclose($file);
		
		$cache_arr_file = $this->cache_dir . $this->cache_arr_pre . $this->groupid . $this->cache_arr_ext;
		$file = fopen($cache_arr_file, 'w');
		fwrite($file, "<?php \n\r return " . var_export($temp, TRUE) . ';');
		fflush($file);
		fclose($file);
	}
	
	/**
	 * 目录是否可写
	 * @param $dir
	 */
	function _writeable($dir) 
	{
		if(!is_dir($dir)) 
		{
			@mkdir($dir, 0777);
		}
		$writeable = 0;
		if(is_dir($dir)) 
		{
			if($fp = @fopen("$dir/test.test", 'w')) 
			{
				@fclose($fp);
				@unlink("$dir/test.test"); 
				$writeable = 1;
			} else 
			{
				$writeable = 0;
			}
		}
		return $writeable;
	}
	
}