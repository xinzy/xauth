<?php
class Settings extends MY_admin
{
	
	function __construct()
	{
		parent::__construct();
		
	}
	
	function index()
	{
		if ($this->IS_POST)
		{
			$sets = $this->input->post('st');
			$this->configmod->save($sets);
			$this->message('settings', '保存成功');
		} else 
		{
			$this->template['sitesettings'] = $this->siteconfig;
			$this->output('settings/index');
		}
	}
	
	/**
	 * 关键词过滤
	 */
	function word()
	{
		if ($this->IS_POST)
		{
			$find = $this->input->post('find');
			$replace = $this->input->post('replace');
			
			if (! empty($find) && ! empty($replace))
			{
				foreach ($find as $key => $val)
				{
					if (! empty($val) && isset($replace[$key]))
					{
						if ($key > 0)
						{
							$data = array(
								'id'	=> $key,
								'find'	=> $val,
								'replace'	=> $replace[$key],
							);
							$this->wordmod->commit($data);
						} else 
						{
							$data = array(
								'find'	=> $val,
								'replace'	=> $replace[$key],
								'admin'	=> $this->user['username'],
							);
							$this->wordmod->commit($data);
						}
					}
				}
			}
			$this->message('settings/word', '修改成功');
		} else 
		{
			$this->template['words'] = $this->wordmod->lists_all();
			$this->output('settings/word');
		}
	}
	
	function delword()
	{
		$id = $this->uri->segment(4);
		$this->wordmod->delete_by_id(intval($id));
		$this->message('settings/word', '删除成功');
	}
	
	function page()
	{
		$this->template['pages'] = $this->pagemod->lists_all();
		
		$this->output('settings/page');
	}
	
	function addpage()
	{
		$this->form_validation->set_rules('subject', '标题', 'required');
		$this->form_validation->set_rules('unique', '唯一标识码', 'required');
		
		if ($this->form_validation->run() !== FALSE)
		{
			$this->_save_page();
		} else 
		{
			$this->output('settings/addpage');	
		}
	}
	
	function editpage()
	{
		$this->form_validation->set_rules('subject', '标题', 'required');
		$this->form_validation->set_rules('unique', '唯一标识码', 'required');
		
		if ($this->form_validation->run() !== FALSE)
		{
			$this->_save_page();
		} else 
		{
			$id = $this->uri->segment(4);
			$page = $this->template['page'] = $this->pagemod->find_by_id($id);
			
			if (empty($page))
			{
				$this->message('settings/page', '页面不存在', 'fail');
			} else 
			{
				$this->output('settings/addpage');
			}
		}
	}
	
	function delpage()
	{
		$id = $this->uri->segment(4);
		$this->pagemod->delete_by_id($id);
		
		$this->message('settings/page', '删除成功');
	}
	
	function _save_page()
	{
		$subject = $this->input->post('subject');
		$keyword = $this->input->post('keyword');
		$content = $this->input->post('content');
		$description = $this->input->post('description');
		$unique = $this->input->post('unique');
		
		$data = array(
			'subject' 	=> $subject,
			'keyword'	=> $keyword,
			'unique'	=> $unique,
			'content'	=> $content,
			'description'	=> $description,
		);
		
		$id = intval($this->input->post('id'));
		if ($id > 0)
		{
			$data['id'] = $id;
		} else 
		{
			$data['authorid'] = $this->user['uid'];
			$data['author'] = $this->user['username'];
			$data['dateline'] = time();
		}
		$this->pagemod->commit($data);
		
		$this->message('settings/page', $id ? '修改成功' : '添加成功');
	}
}