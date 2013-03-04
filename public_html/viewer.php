<?php  
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if (!isset($_GET['id'])):
	die('No id.');
else:
	$id = $_GET['id'];
	if ($id != preg_replace("/[^A-Za-z0-9]/", '', $id)):
		die('Invalid id.');
	endif;
endif;

require_once('mysql_login.php');
	
$db = new mysqli('localhost', $mysql_username, $mysql_password, 'emails');
if($db->connect_errno > 0):
    die('Unable to connect to database [' . $db->connect_error . ']');
endif;

$sql = <<<SQL
	SELECT *
	FROM active
	WHERE id=$id 
SQL;

if(!$result = $db->query($sql)):
    die('There was an error running the query [' . $db->error . ']');
endif;

$row = $result->fetch_assoc();

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>MailHawk Viewer</title>
	<meta name="description" content="MailHawk Viewer">
	<meta name="author" content="SitePoint">
	<link rel="stylesheet" href="css/styles.css?v=1.0">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<?php 
	echo 'Sender: ' . $row['sender'] . "\n";
	echo 'Subject: ' . $row['subject'] . "\n";
	echo 'Body: ' . stripslashes($row['html']) . "\n";
	?>
	<script src="js/scripts.js"></script>
</body>
</html>