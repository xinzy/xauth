<?php

class Myemail
{
	protected $_CI;
	private $debug;
	
	function __construct()
	{
		$this->_CI =& get_instance();
		
		CFG('email', true);
		
		$config['protocol'] = CFG('*myemail_protocol', 'email');//采用smtp方式，方便租用主机的用户
		$config['smtp_host'] = CFG('*myemail_smtp_host', 'email');
		$config['smtp_port'] = CFG('*myemail_smtp_port', 'email');
		$config['smtp_timeout'] = '30';
		$config['smtp_user'] = CFG('*myemail_smtp_user', 'email');
		$config['smtp_pass'] = CFG('*myemail_smtp_pass', 'email');
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$config['mailtype'] = 'html';
		$config['newline'] = "\r\n";
		
		$this->load->library('email');
		$this->email->initialize($config);
		$this->email->from(CFG('*myemail_smtp_user', 'email'), CFG('*myemail_title', 'email'));
		
		$this->debug = CFG('*myemail_debug', 'email');;
	}

	function __get($var)
	{
		return $this->_CI->$var;
	}
	
	function setDebug($debug)
	{
		$this->debug = $debug;
	}
	
	/*
	 * @param $to  邮件发送对象
	 * @param $subject 主题
	 * @param $msg  内容
	 */
	function send($to, $subject, $msg = '')
	{
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($msg);
		
		$this->email->send();
		if ($this->debug)
		{
			echo $this->email->print_debugger();
		}
	}
	
}