#!/usr/local/bin/php -q
<?php
// BEGIN MailParser******************************************
require_once 'MimeMailParser.php';

$path = 'php://stdin';
$Parser = new MimeMailParser();
$Parser->setStream(fopen($path, 'r'));

$to = $Parser->getHeader('to');
$sender = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$textbody = $Parser->getMessageBody('text');
$htmlbody = $Parser->getMessageBody('html');
// HTML Purifier
require_once '/home/birdymai/htmlpurifier/library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
$htmlbody = $purifier->purify($htmlbody);

$date = new DateTime();
$date = $date->format('Y-m-d H:i:s');
//$attachments = $Parser->getAttachments();
$to = preg_replace("/[^a-zA-Z0-9@]/", "", $to); // Some e-mail headers have quotes and other chars, some don't.
$id = substr($to, 0, strpos($to, '@'));
// END MailParser^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

// BEGIN MySQL******************************************
	
// Connect to DB
require_once '/home/birdymai/application/config/mysql_login.php';
try {
  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
  mail('mimo@birdymail.me', 'DB Error in mail', $ex->getMessage());
}

// Check if id exists
try {
  $stmt = $db->prepare('SELECT * FROM users WHERE id=:id');
  $stmt->execute(array(':id' => $id));
  if ($stmt->rowCount() < 1) die();
} catch(PDOException $ex) {
  mail('mimo@birdymail.me', 'DB Error in mail', $ex->getMessage());
}

// Fetch Twitter user associated with e-mail account
try {
  $stmt = $db->prepare('SELECT twitter_user FROM users WHERE id=:id');
  $stmt->execute(array(':id' => $id));
  $row = $stmt->fetch();
  $twitter_user = $row['twitter_user'];
} catch(PDOException $ex) {
	mail('mimo@birdymail.me', 'DB Error in mail', $ex->getMessage());
}

// Add e-mail to DB
try {
    $db->prepare('INSERT INTO active (subject, sender, htmlbody, textbody, id, date) VALUES
                          (:subject, :sender, :htmlbody, :textbody, :id, :date)')
       ->execute(array(':subject' => $subject, 
                        ':sender' => $sender, 
                      ':htmlbody' => $htmlbody, 
                      ':textbody' => $textbody, 
                            ':id' => $id,
                          ':date' => $date));
} catch(PDOException $ex) {
    mail('mimo@birdymail.me', 'DB Error in mail', $ex->getMessage());
}
// END MySQL^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

// BEGIN Twitter******************************************
require_once '/home/birdymai/application/libraries/Tweet.php';

$tweet = new Tweet();
$tweet->setUser($twitter_user);
$tweet->setEggMessage($subject, $id);
$tweet->post();
// END Twitter^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^