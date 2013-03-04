<?php 

// If the user clicked the Run button
if (isset($_POST['submit'])) {

	// Gather their input
	// Escape input values before redisplaying them in the form
	$run = true;
	$method = htmlspecialchars($_POST['method'], ENT_QUOTES);
	$url = htmlspecialchars($_POST['url'], ENT_QUOTES);
	$parameter1 = htmlspecialchars($_POST['parameter1'], ENT_QUOTES);
	$value1 = htmlspecialchars($_POST['value1'], ENT_QUOTES);
	$parameter2 = htmlspecialchars($_POST['parameter2'], ENT_QUOTES);
	$value2 = htmlspecialchars($_POST['value2'], ENT_QUOTES);
	$parameter3 = htmlspecialchars($_POST['parameter3'], ENT_QUOTES);
	$value3 = htmlspecialchars($_POST['value3'], ENT_QUOTES);
	
} else {
	
	// If the form is run for the first time, 
	// or the Clear button is clicked,
	// clear the input fields
	$run = false;
	$method = 'GET';
	$url = '1.1/';	
	$parameter1 = '';
	$value1 = '';
	$parameter2 = '';
	$value2 = '';
	$parameter3 = '';
	$value3 = '';
}
	
// Display the form with empty fields
// or the values entered before Run button was clicked	
print "<form action='console.php' method='post'>";

print "Method <select name='method'>";
if ($method == 'GET') {
	print "<option selected='selected'>GET</option>";
} else {
	print "<option>GET</option>";
}
if ($method == 'POST') {
	print "<option selected='selected'>POST</option>";
} else {
	print "<option>POST</option>";
}
print "</select> ";

print "URL <input type='text' name='url' value='$url' size='30'> ";
print "<input type='submit' name='submit' value='Run' /> ";
print "<input type='submit' name='clear' value='Clear' /><br/>";

// Input fields for 3 parameters are included
// This can easily be changed to more if you wish
print "Parameters:<br/>";
print "<input type='text' name='parameter1' value='$parameter1'> ";
print "<input type='text' name='value1' value='$value1'><br/>";
print "<input type='text' name='parameter2' value='$parameter2'> ";
print "<input type='text' name='value2' value='$value2'><br/>";
print "<input type='text' name='parameter3' value='$parameter3'> ";
print "<input type='text' name='value3' value='$value3'><br/>";
print '</form>';
?>