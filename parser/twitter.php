#!/usr/local/bin/php -q
<?php
// Connect to DB
require_once '/home/birdymai/application/config/mysql_login.php';
try {
  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
  mail('mimo@birdymail.me', 'DB Error in twitter', $ex->getMessage());
}

// Get latest 'stop' request
try {
  $stmt = $db->prepare('SELECT MAX(id) AS max FROM mentions');
  $stmt->execute();
  $row = $stmt->fetch();
  $id = $row['max'];
} catch(PDOException $ex) {
	mail('mimo@birdymail.me', 'DB Error in twitter', $ex->getMessage());
}

require_once '/home/birdymai/application/libraries/Tweet.php';

$tweet = new Tweet();
$mentions = $tweet->getMentions($id);

foreach ($mentions as $mention):
	$text = $mention['text'];
	if (stripos($text, 'stop') !== false || stripos($text, 'remove') !== false || stripos($text, 'delete') !== false || stripos($text, 'unsubscribe') !== false):
		// Deletes all eggs associated with twitter name and records the request tweet
		try {
		    $stmt = $db->prepare('INSERT INTO mentions (id, name, text) VALUES
		                          (:id, :name, :text)');
		    $stmt->execute(array(':id' => $mention['id'], 
		                       ':name' => $mention['user']['screen_name'], 
		                       ':text' => $mention['text']));
		} catch(PDOException $ex) {
		    mail('mimo@birdymail.me', 'DB Error in twitter (stop insert)', $ex->getMessage());
		}
		try {
		  $stmt = $db->prepare('DELETE FROM users WHERE twitter_user = :user');
		  $stmt->execute(array(':user' => $mention['user']['screen_name']));
		} catch(PDOException $ex) {
			mail('mimo@birdymail.me', 'DB Error in twitter (stop delete)', $ex->getMessage());
		}
		$tweet->sendStopMessage($mention['user']['screen_name'], $mention['id']);
	elseif (stripos($text, 'extend') !== false || stripos($text, 'renew') !== false):
		// Renews address for one week
		try {
		    $stmt = $db->prepare('INSERT INTO mentions (id, name, text) VALUES
		                          (:id, :name, :text)');
		    $stmt->execute(array(':id' => $mention['id'], 
		                       ':name' => $mention['user']['screen_name'], 
		                       ':text' => $mention['text']));
		} catch(PDOException $ex) {
		    mail('mimo@birdymail.me', 'DB Error in twitter (extend insert)', $ex->getMessage());
		}
		try {
		    $stmt = $db->prepare('UPDATE users SET expire=DATE_ADD(expire, INTERVAL 1 WEEK), renew=renew+1 WHERE twitter_user=:user');
		    $stmt->execute(array(':user' => $mention['user']['screen_name']));
		} catch(PDOException $ex) {
		    mail('mimo@birdymail.me', 'DB Error in twitter (extend)', $ex->getMessage());
		}
		$tweet->sendExtendMessage($mention['user']['screen_name'], $mention['id']);
	endif;
endforeach;