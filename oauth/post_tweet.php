<?php

// Load the app's OAuth tokens into memory
require 'app_tokens.php';

// Load the tmhOAuth library
require 'tmhOAuth.php';

// Create an OAuth connection to the Twitter API
$connection = new tmhOAuth(array(
  'consumer_key'    => $consumer_key,
  'consumer_secret' => $consumer_secret,
  'user_token'      => $user_token,
  'user_secret'     => $user_secret
));

// Send a tweet
$code = $connection->request('POST', 
	$connection->url('1.1/statuses/update'), 
	array('status' => 'Hello Twitter'));

// A response code of 200 is a success
if ($code == 200) {
  print "Tweet sent";
} else {
  print "Error: $code";
}

?>