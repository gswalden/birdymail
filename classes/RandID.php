<?php
Class RandID($db)
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
		
		/*$sql = <<<SQL
			SELECT count(*)
			FROM active
			WHERE id=$id 
		SQL;*/

		if(!$result = $db->query("$SELECT count(*) FROM active WHERE id=$id")):
	    	die('There was an error running the query [' . $db->error . ']');
		endif;
		
		$row = $result->fetch_row();
		
		if ($row[0] > 0):
		    randID($db);
		elseif:
		    return $id;
		endif;
	}

	
}