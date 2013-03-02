<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// BEGIN MailParser******************************************
require_once('classes/MimeMailParser.php');
require_once('classes/Tweet.php');

$path = 'classes/mail.txt';
$Parser = new MimeMailParser();
//$Parser->setStream(fopen($path));
$Parser->setPath($path);

$to = $Parser->getHeader('to');
$from = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$text = $Parser->getMessageBody('text');
$html = $Parser->getMessageBody('html');
$attachments = $Parser->getAttachments();
// END MailParser^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

$id = substr($to, 0, strpos($to, '@'));

// BEGIN MySQL******************************************
require_once('mysql_login.php');
	
$db = new mysqli('localhost', $mysql_username, $mysql_password, 'emails');

if($db->connect_errno > 0):
    die('Unable to connect to database [' . $db->connect_error . ']');
endif;

$text = $db->escape_string($text);
$html = $db->escape_string($html);

$sql = <<<SQL
	SELECT twitter_user
	FROM active
	WHERE id = $id 
SQL;

if(!$result = $db->query($sql)):
    die('There was an error running the query [' . $db->error . ']');
endif;

$row = $result->fetch_assoc();
$twitter_user = $row['twitter_user'];
$result->free();
$datetime = new DateTime();
$datetime->add(new DateInterval('P1D'));
$expire = $datetime->format('Y-m-d H:i:s');

$sql = <<<SQL
    UPDATE active
    SET expire='$expire', subject='$subject', sender='$from', htmlbody='$html', textbody='$text'
    WHERE id = $id
SQL;

if(!$result = $db->query($sql)):
    die('There was an error running the query [' . $db->error . ']');
endif;
// END MySQL^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

$tweet = new Tweet();
$tweet->setUser($twitter_user);
$tweet->setMessage($subject);
$tweet->post($id);
?>