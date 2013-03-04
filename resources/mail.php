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
//$attachments = $Parser->getAttachments();

$id = substr($to, 1, strpos($to, '@') - 1);
mail("gswalden@yahoo.com", 'got 4', $id . ' ' . $subject);
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
  echo '1An Error occured!' . $ex->getMessage();
  mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Fetch Twitter user associated with e-mail account
try {
  $stmt = $db->prepare('SELECT twitter_user FROM users WHERE id=:id');
  $stmt->execute(array(':id' => $id));
  $row = $stmt->fetch();
  $twitter_user = $row['twitter_user'];
} catch(PDOException $ex) {
	echo '2An Error occured!' . $ex->getMessage();
	mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Add e-mail to DB
try {
    $stmt = $db->prepare('INSERT INTO active(subject, sender, htmlbody, textbody, id) VALUES
                          :subject, :sender, :htmlbody, :textbody, :id');
    $stmt->execute(array(':subject' => $subject, 
                          ':sender' => $sender, 
                        ':htmlbody' => $htmlbody, 
                        ':textbody' => $textbody, 
                              ':id' => $id));
} catch(PDOException $ex) {
    echo '3An Error occured!';
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}
// END MySQL^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

// BEGIN Twitter******************************************
require_once('/home/birdymai/resources/library/Tweet.class.php');

$tweet = new Tweet();
$tweet->setUser($twitter_user);
$tweet->setMessage($subject);
$tweet->post($id);
// END Twitter^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^