#!/usr/local/bin/php -q
<?php
mail("gswalden@gmail.com", 'Running', '');
require_once '/home/birdymai/application/libraries/Tweet.php';

$tweet = new Tweet();
$data = $tweet->getMentions();

mail("gswalden@gmail.com", 'Mentions', print_r($data, true));
