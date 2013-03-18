#!/usr/local/bin/php
<?php
// Connect to DB
require_once '/home/birdymai/application/config/mysql_login.php';
try {
  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
  echo 'An Error occured!';
  mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}
try {
  $stmt = $db->prepare('SELECT MAX(id) FROM mentions');
  $stmt->execute();
  $row = $stmt->fetch();
  $id = $row['id'];
} catch(PDOException $ex) {
	echo 'An Error occured!' . $ex->getMessage();
	mail($errorEMail, 'DB Error', $ex->getMessage());
}

require_once '/home/birdymai/application/libraries/Tweet.php';

$tweet = new Tweet();
$mentions = $tweet->getMentions($id);

foreach ($mentions as $mention) 
{
	$text = $mention['text'];
	if (stripos($text, 'stop') !== false || stripos($text, 'remove') !== false || stripos($text, 'delete') !== false || stripos($text, 'unsubscribe') !== false):

	endif;
}
