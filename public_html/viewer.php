<?php  
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

if (!isset($_GET['id'])):
	die('No id.');
else:
	$id = $_GET['id'];
	if (strcmp($id, preg_replace("/[^0-9]/", '', $id)) != 0):
		header('Location: http://www.birdymail.me/');
	endif;
endif;

require_once('/home/birdymai/resources/mysql_login.php');

try {
  $stmt = $db->prepare('SELECT * FROM active WHERE id=:id');
  $stmt->execute(array(':id' => $id));
  if ($stmt->rowCount() < 1):
    header('Location: http://www.birdymail.me/');
  endif;
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
	echo 'An Error occured!' . $ex->getMessage();
	mail($errorEMail, 'DB Error', $ex->getMessage());
}

?>
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
	foreach ($rows as $row): 
		echo 'Sender: ' . $row['sender'] . "\n";
		echo 'Subject: ' . $row['subject'] . "\n";
		echo 'Body: ' . stripslashes($row['htmlbody']) . "\n";
	endforeach;
	?>
	<script src="js/scripts.js"></script>
</body>
</html>