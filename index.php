<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// BEGIN MailParser
require_once('classes/MimeMailParser.php');

$path = '/var/www/gswalden.com/mailhawk/classes/mail.txt';
$Parser = new MimeMailParser();
$Parser->setPath($path);

$to = $Parser->getHeader('to');
$from = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$text = $Parser->getMessageBody('text');
$html = $Parser->getMessageBody('html');
$attachments = $Parser->getAttachments();
// END MailParser
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