#!/usr/local/bin/php -q
<?php
// BEGIN MailParser******************************************
require_once('/home/birdymai/resources/library/MimeMailParser.class.php');

$path = 'php://stdin';
$Parser = new MimeMailParser();
$Parser->setStream(fopen($path, 'r'));

$to = $Parser->getHeader('to');
$sender = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$textbody = $Parser->getMessageBody('text');
$htmlbody = $Parser->getMessageBody('html');
$date = new DateTime();
$date = $date->format('Y-m-d H:i:s');
//$attachments = $Parser->getAttachments();
$to = preg_replace("/[^a-zA-Z0-9@]/", "", $to); // Some e-mail headers have quotes and other chars, some don't.
$id = substr($to, 0, strpos($to, '@'));
// END MailParser^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

// BEGIN MySQL******************************************
	
// Connect to DB
require_once('/home/birdymai/resources/mysql_login.php');

// Check if id exists
try {
  $stmt = $db->prepare('SELECT * FROM users WHERE id=:id');
  $stmt->execute(array(':id' => $id));
  if ($stmt->rowCount() < 1):
    die();
  endif;
} catch(PDOException $ex) {
  echo 'An Error occured!' . $ex->getMessage();
  mail($errorEMail, 'DB Error', $ex->getMessage());
}

// Fetch Twitter user associated with e-mail account
try {
  $stmt = $db->prepare('SELECT twitter_user FROM users WHERE id=:id');
  $stmt->execute(array(':id' => $id));
  $row = $stmt->fetch();
  $twitter_user = $row['twitter_user'];
} catch(PDOException $ex) {
	echo 'An Error occured!' . $ex->getMessage();
	mail($errorEMail, 'DB Error', $ex->getMessage());
}

// Add e-mail to DB
try {
    $stmt = $db->prepare('INSERT INTO active (subject, sender, htmlbody, textbody, id, date) VALUES
                          (:subject, :sender, :htmlbody, :textbody, :id, :date)');
    $stmt->execute(array(':subject' => $subject, 
                          ':sender' => $sender, 
                        ':htmlbody' => $htmlbody, 
                        ':textbody' => $textbody, 
                              ':id' => $id,
                            ':date' => $date));
} catch(PDOException $ex) {
    echo 'An Error occured!';
    mail($errorEMail, 'DB Error', $ex->getMessage());
}
// END MySQL^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

// BEGIN Twitter******************************************
require_once('/home/birdymai/resources/library/Tweet.class.php');

$tweet = new Tweet();
$tweet->setUser($twitter_user);
$tweet->setMessage($subject);
$tweet->post($id);
// END Twitter^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^