<?php
$mysql_username = 'birdymai_mimo';
$mysql_password = 'Z6qm4Hj@E8n%6lxahBue#7Ok$2X@OQ';

try {
	$db = new PDO('mysql:dbname=birdymai_emails;host=localhost', $mysql_username, $mysql_password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
	echo 'An Error occured!';
	mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}
?>