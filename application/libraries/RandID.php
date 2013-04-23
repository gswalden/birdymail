<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class RandID
{
	private $CI;

	public function __construct() 
	{
		$this->CI =& get_instance(); // sets CI as refernce to framework (in order to use)		
	}

	public function getRandID()
	{
		/*$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$id = '';
		$max = strlen($characters) - 1;
		
		for ($i = 0; $i < 10; $i++):
		      $id .= $characters[mt_rand(0, $max)];
		endfor;*/
		
		$id = mt_rand(100000, 999999999); // SQL table set to max 9 length
		
		// $query = $this->CI->Creator->getUser($id); // SELECT * FROM users WHERE id=$id
		
		// Checks db for id conflict, runs function again if one is found found
		if ($this->CI->Creator->isUser($id)) 
			$this->getRandID();
		return $id;
	}	
}