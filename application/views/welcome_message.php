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
	<div id="titlebox">
	BirdyMail <br />
	<div id="list">1. Submit Twitter name<br />
	2. Use temporary address<br />
	3. Wait for tweets from @BirdyMailMe
	</div></div>
	<div id="emailbox">
	<?php 
	echo form_open('create');
	echo '<span id="atsymbol">@</span>' . form_input($input);
	echo form_submit($submit, 'Go!');
	echo form_close();
	?>
	</div>
	<div id="footer">
	photo "Top of the Rock" courtesy of <a href="http://interfacelift.com/user/13683/benwall.html">BenWall</a>, via <a href="http://interfacelift.com/wallpaper/details/3204/top_of_the_rock.html">InterfaceLIFT</a>
	</div>
	<script src="js/scripts.js"></script>
</body>
</html>