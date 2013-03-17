<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BirdyMail Egg Layer</title>
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
	<div id="eggbox">
		<?php	echo 'Weclome to the nest!<br />Your BirdyMail egg is<br /><input type="text" value="' . $id . '@birdymail.me" readonly>' ?>
		<div id="d_clip_button" data-clipboard-text=<?php echo '"' . $id . '@birdymail.me' . '"' ?> title="click to copy">COPY</div>
	</div>
    <script type="text/javascript" src="http://www.birdymail.me/js/ZeroClipboard.js"></script>
    <script language="JavaScript">
    	ZeroClipboard.setDefaults( { moviePath: 'http://www.birdymail.me/js/ZeroClipboard.swf', trustedDomains: 'birdymail.me' } );
      	var clip = new ZeroClipboard( document.getElementById('d_clip_button') );
    </script>
</body>
</html>