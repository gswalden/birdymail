<?php
// BEGIN MailParser
require_once('MimeMailParser.class.php');

$path = 'path/to/mail.txt';
$Parser = new MimeMailParser();
$Parser->setPath($path);

$to = $Parser->getHeader('to');
$from = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$text = $Parser->getMessageBody('text');
$html = $Parser->getMessageBody('html');
$attachments = $Parser->getAttachments();
// END MailParser

// BEGIN Codebird
require_once ('php/codebird.php');
Codebird::setConsumerKey('WY5DGrh9ipWj8UngDdhXJg', 'zTDypsqR3dJ7rxlA2CywvbfMCoXoZaUc0Ky1H8ulI'); // static, see 'Using multiple Codebird instances'

$cb = Codebird::getInstance();
$cb->setToken('1227070020-znV3OhwIxitVmLlDZkeNJen7NQWzHqmlzXfUv81', 'pNdPvydRtAPVIBNT7K7FwHVWNBhW2eZCkRJRxHq0');

$reply = $cb->statuses_update("status=@Username --$subject-- link");
// END Codebird
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>mail2tweet</title>
	<meta name="description" content="mail2tweet">
	<meta name="author" content="mimo">
	<link rel="stylesheet" href="css/styles.css?v=1.0">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	Possible uses:
	<ul>
		<li>Sign up for "when it's ready" announcements</li>
		<li>Mailing lists</li>
		<li>Throw-away address</li>
	</ul>
	<script src="js/scripts.js"></script>
</body>
</html>