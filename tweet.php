<?php 
require_once ('php/codebird.php');
Codebird::setConsumerKey('WY5DGrh9ipWj8UngDdhXJg', 'zTDypsqR3dJ7rxlA2CywvbfMCoXoZaUc0Ky1H8ulI'); // static, see 'Using multiple Codebird instances'

$cb = Codebird::getInstance();
$cb->setToken('1227070020-znV3OhwIxitVmLlDZkeNJen7NQWzHqmlzXfUv81', 'pNdPvydRtAPVIBNT7K7FwHVWNBhW2eZCkRJRxHq0');

$reply = $cb->statuses_update('status=@Username --$subject-- link');
?>