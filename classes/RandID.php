<?php
class RandID
{
	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function getRandID()
	{
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$id = '';
		$max = strlen($characters) - 1;
		
		for ($i = 0; $i < 6; $i++):
		      $id .= $characters[mt_rand(0, $max)];
		endfor;
		
		$sql = "SELECT count(*) FROM active WHERE id='$id'";

		if(!$result = $this->db->query($sql)):
	    	die('There was an error running the query [' . $this->db->error . ']');
		endif;
		
		$row = $result->fetch_row();
		
		if ($row[0] > 0):
		    getRandID();
		else:
		    return $id;
		endif;
	}

	
}