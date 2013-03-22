#!/usr/local/bin/php -q
<?php
// Connect to DB
require_once '/home/birdymai/application/config/mysql_login.php';
try {
  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
  mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Delete expired users
try {
	// Moves e-mails from table 'active' to 'inactive'
	$stmt_get = $db->prepare('SELECT id FROM users WHERE expire < NOW()');
	$stmt_get->execute();
	if ($stmt_get->rowCount() > 0)
		while ($id = $stmt_get->fetch()):
			$stmt_move = $db->prepare('INSERT INTO inactive (id, sender, subject, textbody, htmlbody, date) (SELECT id, sender, subject, textbody, htmlbody, date FROM active WHERE id = :id)');
			$stmt_move->execute(array(':id' => $id[0]));
			if ($stmt_move->rowCount() > 0)
				$db->prepare('DELETE FROM active WHERE id = :id')->execute(array(':id' => $id[0]));
			$db->prepare('DELETE FROM users WHERE id = :id')->execute(array(':id' => $id[0]));
		endwhile;
} catch(PDOException $ex) {
	mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}