#!/usr/local/bin/php -q
<?php
// BEGIN Twitter******************************************
require_once '/home/birdymai/application/libraries/Tweet.php';

$tweet = new Tweet();
$urlLen = $tweet->urlLength();

// Connect to DB
require '/home/birdymai/application/config/mysql_login.php';
try {
  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
  mail('mimo@birdymail.me', 'DB Error in url_length', $ex->getMessage());
}
if ($urlLen !== false):
	try {
	    $stmt = $db->prepare('UPDATE config SET value=:urlLen, updated=NOW() WHERE name=:url_length');
	    $stmt->execute(array(':urlLen' => $urlLen,
	    					 ':url_length' => 'url_length'));
	} catch(PDOException $ex) {
	    mail('mimo@birdymail.me', 'DB Error in url_length', $ex->getMessage());
	}
endif;