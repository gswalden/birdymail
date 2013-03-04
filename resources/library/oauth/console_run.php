<?php

// Connect to the API with OAuth tokens
require 'app_tokens.php';  
require 'tmhOAuth.php';

$connection = new tmhOAuth(array(
  'consumer_key' => $consumer_key,
  'consumer_secret' => $consumer_secret,
  'user_token' => $user_token,
  'user_secret' => $user_secret
)); 
  
// Accumulate the parameters and their matching values
// in an array to be used in the API request
$array = array();
if (! empty($parameter1)) {
	$array = array_merge($array,array($parameter1=>$value1));
}
if (! empty($parameter2)) {
	$array = array_merge($array,array($parameter2=>$value2));
}
if (! empty($parameter3)) {
	$array = array_merge($array,array($parameter3=>$value3));
}
// Add more parameters here if you included more in the form

if (sizeof($array)==0) {
	// There were no parameters entered
	$connection->request($method, 
		$connection->url($url));	
} else {
	// Use the parameters entered on the form
	$code = $connection->request($method, 
		$connection->url($url),$array);
}

// Display the response HTTP code
$code = $connection->response['code'];
print "<strong>Code:</strong> $code<br/>";

// Display the API response as an array
print "<strong>Response:</strong><pre style='word-wrap: break-word'>";
print_r(json_decode($connection->response['response'],true));
print "</pre><br/>";;
?>