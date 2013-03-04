<?php

function validate_twitter_username($username)
{
	require_once('app_tokens.php');
	require_once('tmhOAuth.php');

	$connection = new tmhOAuth(array(
	  'consumer_key'    => $consumer_key,
	  'consumer_secret' => $consumer_secret,
	  'user_token'      => $user_token,
	  'user_secret'     => $user_secret
	));

	// Get @justinbieber's account info
	$connection->request('GET', $connection->url('1.1/users/show'), array(
	  'screen_name' => "$username"
	));

	// Get the HTTP response code for the API request
	if ($connection->response['code'] == 200):
		return true;
	else:
		return false;
	endif;
}