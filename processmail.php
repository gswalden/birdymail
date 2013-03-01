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
// MySQL
?>