#!/usr/bin/php -q
<?php
  
// BEGIN MailParser******************************************
require_once('classes/MimeMailParser.php');

$path = 'php://stdin';
$Parser = new MimeMailParser();
$Parser->setStream(fopen($path));

$to = $Parser->getHeader('to');
$from = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$text = $Parser->getMessageBody('text');
$html = $Parser->getMessageBody('html');
$attachments = $Parser->getAttachments();
// END MailParser^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

$id = substr($to, 0, strpos($to, '@'));

// BEGIN MySQL******************************************
$db = new mysqli('localhost', 'root', 'RrhBVKJKjPdmFyN7', 'emails');

if($db->connect_errno > 0):
    die('Unable to connect to database [' . $db->connect_error . ']');
endif;

$sql = <<<SQL
    INSERT INTO `active` (from, subject, text, html, expire) 
    VALUES ($from, $subject, $text, $html, $expire) 
SQL;

if(!$result = $db->query($sql)):
    die('There was an error running the query [' . $db->error . ']');
endif;
// END MySQL^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
?>