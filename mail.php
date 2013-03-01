<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// BEGIN MailParser******************************************
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
// END MailParser^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

$id = 348492384989;
$twitterUser = 'ravegoboom';
$twitterMessage = 'You\'ve got mail: ';
$viewerURL = 'http://www.studiomimo.com/mailhawk/viewer.php?id=' . $id;
$charCount = 140 - (1 + strlen($twitterUser) + 1 + strlen($twitterMessage) + 1 + 25);

if (strlen($subject) > $charCount):
	$subject = substr($subject, 0, $charCount - 3) . '…';
endif;

$twitterMessage = "@$twitterUser " . $twitterMessage . substr($subject, 0, $charCount) . ' ' . $viewerURL;
echo strlen($twitterMessage) . ': ' .$twitterMessage;

// BEGIN TwitterOAuth******************************************
// Load the app's OAuth tokens into memory
require 'oauth/app_tokens.php';

// Load the tmhOAuth library
require 'oauth/tmhOAuth.php';

// Create an OAuth connection to the Twitter API
$connection = new tmhOAuth(array(
	'consumer_key'    => $consumer_key,
	'consumer_secret' => $consumer_secret,
	'user_token'      => $user_token,
	'user_secret'     => $user_secret
	));

$code = $connection->request('GET', $connection->url('1.1/help/configuration.json'));
echo $code;
if ($code == 200):
	$response = $connection->response['response'];
	$urlLen = intval(substr($response, strpos($response, 'short_url_length') + 18, 2));
	unset($code);
else:
	print "Error: $code";
endif;

// Send a tweet
$code = $connection->request('POST', 
	$connection->url('1.1/statuses/update'), 
	array('status' => $twitterMessage));

// A response code of 200 is a success
if ($code == 200) {
	print "Tweet sent";
} else {
	print "Error: $code";
}
// END TwitterOAuth^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
?>