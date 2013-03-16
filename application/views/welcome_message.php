<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BirdyMail</title>
	<meta name="description" content="BirdyMail">
	<meta name="author" content="mimo">
	<link rel="stylesheet" href="http://birdymail.me/css/styles.css">
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
	<?php 
	echo form_open('create');
	echo form_input($data);
	?>
	<script src="js/scripts.js"></script>
</body>
</html>