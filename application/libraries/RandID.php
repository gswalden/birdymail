<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class RandID
{
	private $CI;

	public function __construct() {
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
		
		$id = mt_rand(100000, 999999999);
		
		$query = $this->CI->db->get_where('users', array(id => $id)); // SELECT * FROM users WHERE id=$id
		
		if ($query->count_all_results() > 0):
		    $this->getRandID();
		else:
		    return $id;
		endif;
	}	
}