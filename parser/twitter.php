#!/usr/local/bin/php -q
<?php
/*
// Our custom error handler
function nettuts_error_handler($number, $message, $file, $line, $vars)

{
	$email = "
		<p>An error ($number) occurred on line 
		<strong>$line</strong> and in the <strong>file: $file.</strong> 
		<p> $message </p>";
		
	$email .= "<pre>" . print_r($vars, 1) . "</pre>";
	
	$headers = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	// Email the error to someone...
	error_log($email, 1, 'gswalden@gmail.com', $headers);

	// Make sure that you decide how to respond to errors (on the user's side)
	// Either echo an error message, or kill the entire project. Up to you...
	// The code below ensures that we only "die" if the error was more than
	// just a NOTICE. 
	if ( ($number !== E_NOTICE) && ($number < 2048) ) {
		die("There was an error. Please try again later.");
	}
}

// We should use our custom function to handle errors.
set_error_handler('nettuts_error_handler'); */

// Connect to DB
require '/home/birdymai/application/config/production/mysql_login.php';
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

function insertMention(&$db, $mention)
{
	try {
	    $db->prepare('INSERT INTO mentions (id, name, text)
	    						VALUES (:id, :name, :text)')
	    ->execute(array(':id' => $mention['id'], 
	    			  ':name' => $mention['user']['screen_name'], 
	        		  ':text' => $mention['text']));
	} catch(PDOException $ex) {
	    mail('mimo@birdymail.me', 'DB Error in twitter (insertMention)', $ex->getMessage());
	}
}

foreach ($mentions as $mention):
	$text = $mention['text'];
	if (stripos($text, 'stop') !== false || stripos($text, 'remove') !== false || stripos($text, 'kill') !== false || stripos($text, 'delete') !== false || stripos($text, 'unsubscribe') !== false):
		// Deletes all eggs associated with twitter name and records the request tweet
		insertMention($db, $mention);
		try {
		  $stmt = $db->prepare('DELETE FROM users WHERE twitter_user = :user');
		  $stmt->execute(array(':user' => $mention['user']['screen_name']));
		} catch(PDOException $ex) {
			mail('mimo@birdymail.me', 'DB Error in twitter (stop delete)', $ex->getMessage());
		}
		if ($stmt->rowCount() > 0)
			$tweet->setStopMessage($mention['user']['screen_name'], $mention['id']);
		else
			$tweet->setReplyMessage('@' . $mention['user']['screen_name'] . ' You don\'t have any eggs to crack!', $mention['id']);
		$tweet->post();
	elseif (stripos($text, 'extend') !== false || stripos($text, 'renew') !== false):
		// Renews address for one week
		insertMention($db, $mention);
		try {
		    $stmt = $db->prepare('UPDATE users SET expire=DATE_ADD(expire, INTERVAL 1 WEEK), renew=renew+1 WHERE twitter_user=:user');
		    $stmt->execute(array(':user' => $mention['user']['screen_name']));
		} catch(PDOException $ex) {
		    mail('mimo@birdymail.me', 'DB Error in twitter (extend)', $ex->getMessage());
		}
		if ($stmt->rowCount() > 0)
			$tweet->setExtendMessage($mention['user']['screen_name'], $mention['id']);
		else
			$tweet->setReplyMessage('@' . $mention['user']['screen_name'] . ' You don\'t have any living eggs!', $mention['id']);
		$tweet->post();
	endif;
endforeach;