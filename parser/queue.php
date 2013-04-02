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
	$stmt_get = $db->prepare("SELECT * FROM twitter_queue");
	$stmt_get->execute();
	if ($stmt_get->rowCount() > 0):
		try {
		  $stmt = $db->prepare("SELECT num_value FROM config WHERE name=:tweets_today");
		  $stmt->execute(array(":tweets_today" => "tweets_today"));
		  $tweets_today = $stmt->fetch();
		  $tweets_today = $tweets_today[0];
		} catch(PDOException $ex) {
		  mail("mimo@birdymail.me", "DB Error in QUEUE", $ex->getMessage());
		}
		date_default_timezone_set("America/New_York");
		$seconds = time() - strtotime("today");
		while ($row = $stmt_get->fetch()):
			if (($tweets_today * 86.4) < $seconds):
				require_once '/home/birdymai/application/libraries/Tweet.php';
				$tweet = new Tweet();
				$tweet->setUser($row["user"]);
				if ($tweet->getUser() === FALSE)
					continue;
				$tweet->setMessage(unserialize($row["message"]));
				$tweet->post(TRUE);
				$tweets_today++;
				try {
				  $db->prepare("DELETE FROM twitter_queue WHERE id=:id")
				  	 ->execute(array(":id" => $row["id"]));
				} catch(PDOException $ex) {
				  mail("mimo@birdymail.me", "DB Error in QUEUE", $ex->getMessage());
				}
			endif;   
		endwhile;
	endif;
} catch(PDOException $ex) {
	mail('mimo@birdymail.me', 'DB Error in Queue', $ex->getMessage());
}