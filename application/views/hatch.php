<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BirdyMail Egg Hatcher</title>
	<meta name="description" content="BirdyMail">
	<meta name="author" content="mimo">
		<link rel="stylesheet" href="http://birdymail.me/css/styles.css">

  	<!--[if lt IE 9]>
  	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  	<![endif]-->
</head>
<body>
	<div id="emailbox">
	<?php
	$i = 0;
	foreach ($query->result() as $row): 
		echo 'Sender: ' . $row->sender . '<br />';
		echo 'Subject: ' . $row->subject . '<br />';
		echo 'Body: ' . stripslashes($row->htmlbody) . '<br />';
		$i++;
		echo "end email $i<hr />";
	endforeach;
	?>
	</div>
	<script src="js/scripts.js"></script>
</body>
</html>