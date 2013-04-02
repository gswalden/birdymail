#!/usr/local/bin/php -q
<?php
// Connect to DB
require '/home/birdymai/application/config/mysql_login.php';
try {
  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
  mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

try {
  $db->prepare("UPDATE config SET num_value=0 WHERE name=:tweets_today")
     ->execute(array(":tweets_today" => "tweets_today"));
} catch(PDOException $ex) {
  mail("mimo@birdymail.me", "DB Error in Reset", $ex->getMessage());
}