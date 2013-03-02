<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>MailHawk</title>
	<meta name="description" content="MailHawk">
	<meta name="author" content="mimo">
	<link rel="stylesheet" href="css/styles.css?v=1.0">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<style>
		body {
        	padding: 10px;
        	background-color: #F4F4F4;
      }
      input[type=text] {
          width: 600px;
          height: 50px;
          padding: 5px;
          padding-left: 10px;
          outline: none;
          border: 2px solid #999999;
          border-radius: 5px;
          background-color: #FBFBFB;
          font-family: Cambria, Cochin, Georgia, serif;
          font-size: 32px;
          -webkit-transition: background-color .2s ease-in,
                              background-position .2s ease-in,
                              box-shadow .3s ease-in;
           
          -moz-transition: background-color .2s ease-in,
                           background-position .2s ease-in,
                           box-shadow .3s ease-in;
           
          -o-transition: background-color .2s ease-in,
                         background-position .2s ease-in,
                         box-shadow .3s ease-in;
           
          -ms-transition: background-color .2s ease-in,
                          background-position .2s ease-in,
                          box-shadow .3s ease-in;
           
          transition: background-color .2s ease-in,
                      background-position .2s ease-in,
                      box-shadow .3s ease-in;          
      }
      input[type=text]:focus {
          background-color: #FFFFFF;
          border-color: #333333;
          box-shadow: 0px 0px 25px -2px #333;
      }
	</style>
</head>
<body>
	Possible uses:
	<ul>
		<li>Sign up for "when it's ready" announcements</li>
		<li>Mailing lists</li>
		<li>Throw-away address</li>
	</ul>
	<form method="post" action="done.php">
		<input type="text" name="twitter_name" autofocus="autofocus" maxlength="16" placeholder="Enter Twitter name…">
	</form>
	<script src="js/scripts.js"></script>
</body>
</html>