<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BirdyMail!</title>
	<meta name="description" content="BirdyMail">
	<meta name="author" content="Mimo">
	<link rel="stylesheet" href="http://birdymail.me/css/styles.css">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<div id="titlebox">
		BirdyMail <br />
		<div id="list">
			1. Submit Twitter name<br />
			2. Use temporary address<br />
			3. Wait for tweets from <a href="http://twitter.com/BirdyMailMe">@BirdyMailMe</a>
		</div>
	</div>
	<div id="emailbox">
		<?php 
		echo form_open('layegg');
		echo '<span id="atsymbol">@</span>' . form_input($input);
		echo form_submit($submit, 'go!');
		echo form_close();
		?>
	</div>
	<?php if ($this->uri->segment(1) == 'badegg'): ?> 
	<div id="errorbox">
		<?php   $seg2 = $this->uri->segment(2);
			 	if (is_numeric($seg2)) 
			 		echo "The egg $seg2@birdymail.me has not been laid!";
				else 
					echo "Invalid Twitter name <a href=\"http://twitter.com/$seg2\">@$seg2</a>."; ?>
	</div>
	<?php endif; ?>
	<div id="footer">
		photo "Top of the Rock" courtesy of <a href="http://interfacelift.com/user/13683/benwall.html">BenWall</a>, via <a href="http://interfacelift.com/wallpaper/details/3204/top_of_the_rock.html">InterfaceLIFT</a>
	</div>
	<script src="js/scripts.js"></script>
</body>
</html>