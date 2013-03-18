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

// Get latest 'stop' request
try {
  $stmt = $db->prepare('SELECT MAX(id) AS max FROM mentions');
  $stmt->execute();
  $row = $stmt->fetch();
  $id = $row['max'];
} catch(PDOException $ex) {
	mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

require_once '/home/birdymai/application/libraries/Tweet.php';

$tweet = new Tweet();
$mentions = $tweet->getMentions($id);

// Deletes all eggs associated with twitter name and records the request tweet
foreach ($mentions as $mention):
	$text = $mention['text'];
	if (stripos($text, 'stop') !== false || stripos($text, 'remove') !== false || stripos($text, 'delete') !== false || stripos($text, 'unsubscribe') !== false):
		try {
		    $stmt = $db->prepare('INSERT INTO mentions (id, name, text) VALUES
		                          (:id, :name, :text)');
		    $stmt->execute(array(':id' => $mention['id'], 
		                       ':name' => $mention['user']['screen_name'], 
		                       ':text' => $mention['text']));
		} catch(PDOException $ex) {
		    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
		}
		try {
		  $stmt = $db->prepare('DELETE FROM users WHERE twitter_user = :user');
		  $stmt->execute(array(':user' => $mention['user']['screen_name']));
		} catch(PDOException $ex) {
			mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
		}
		$tweet->sendStopMessage($mention['user']['screen_name'], $mention['id']);
	endif;
endforeach;
/*try {
    $stmt = $db->prepare('INSERT INTO mentions (id, name, text) VALUES
                          (:id, :name, :text)');
    $stmt->execute(array(':id' => $mentions[0]['id'], 
                       ':name' => $mentions[0]['user']['screen_name'], 
                       ':text' => $mentions[0]['text']));
} catch(PDOException $ex) {
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}*/