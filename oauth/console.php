<?php

print "<h2>API Console</h2>";

// Display the input form
$run = false;
require 'console_form.php';

// If the user clicked the Run button
// Execute the API request they entered
if ($run) {
	require 'console_run.php';
}

?>