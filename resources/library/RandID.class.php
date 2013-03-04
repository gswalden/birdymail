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
		
		try {
		  $stmt = $this->db->prepare('SELECT * FROM users WHERE id=:id');
		  $stmt->execute(array(':id' => $id));
		} catch(PDOException $ex) {
		  echo 'An Error occured!';
		  mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
		}

		if ($stmt->rowCount() > 0):
		    getRandID();
		else:
		    return $id;
		endif;
	}

	
}