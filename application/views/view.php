<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>BirdyMail Viewer</title>
	<meta name="description" content="MailHawk Viewer">
	<meta name="author" content="SitePoint">
	<link rel="stylesheet" href="css/styles.css?v=1.0">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
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
	<script src="js/scripts.js"></script>
</body>
</html>