<?php

if(!function_exists('h')) {
	function h($s) { return htmlspecialchars($s); }
}

class PhpTemplate
{
	private $data = array();

	public function __construct($file)
	{
		$this -> file = $file;
	}

	public function set($key, $value = null)
	{
		if(!is_null($value))
		{
			$key = array($key => $value);
		}
		
		$this->data = array_merge($this->data, (array)$key);
	}

	public function render($___data = array())
	{
		extract(array_merge($this->data, (array)$___data));
		unset($___data);
		
		ob_start();
		include($this->file);
		return ob_get_clean();
	}
}

?>