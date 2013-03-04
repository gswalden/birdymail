#!/usr/local/bin/php -q
<?php
// BEGIN MailParser******************************************
require_once('resources/library/MimeMailParser.class.php');

$path = 'php://stdin';
$Parser = new MimeMailParser();
$Parser->setStream(fopen($path));

$to = $Parser->getHeader('to');
$sender = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$textbody = $Parser->getMessageBody('text');
$htmlbody = $Parser->getMessageBody('html');
$attachments = $Parser->getAttachments();

$id = substr($to, 0, strpos($to, '@'));
// END MailParser^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

// BEGIN MySQL******************************************
require_once('resources/mysql_login.php');
	
// Connect to DB
try {
	$db = new PDO('mysql:localhost;dbname=emails', $mysql_username, $mysql_password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
	echo 'An Error occured!';
	mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Check if id exists
try {
  $stmt = $db->prepare('SELECT * FROM active WHERE id=:id');
  $stmt->execute(array(':id' => $id));
  if ($stmt->rowCount() < 1):
    die();
  endif;
} catch(PDOException $ex) {
  echo 'An Error occured!';
  mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Fetch Twitter user associated with e-mail account
try {
	foreach($db->query("SELECT twitter_user FROM active WHERE id = $id") as $row):
	    $twitter_user = $row['twitter_user'];
	endforeach;
} catch(PDOException $ex) {
	echo 'An Error occured!';
	mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Add e-mail to DB
try {
    $stmt = $db->prepare('UPDATE active SET subject = :subject, 
                                      sender = :sender, 
                                    htmlbody = :htmlbody, 
                                    textbody = :textbody 
                                    WHERE id = :id');
    $stmt->execute(array(':subject' => $subject, 
                     ':sender' => $sender, 
                   ':htmlbody' => $htmlbody, 
                   ':textbody' => $textbody, 
                         ':id' => $id));
} catch(PDOException $ex) {
    echo 'An Error occured!';
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}
// END MySQL^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

// BEGIN Twitter******************************************
require_once('resources/library/Tweet.class.php');

$tweet = new Tweet();
$tweet->setUser($twitter_user);
$tweet->setMessage($subject);
$tweet->post($id);
// END Twitter^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
?>