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
  $stmt = $db->prepare('DELETE FROM users WHERE expire < NOW()');
  $stmt->execute();
} catch(PDOException $ex) {
	mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}