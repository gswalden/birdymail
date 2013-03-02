<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

require_once('classes/RandID.php');
require_once('classes/ValidTwitter.php');

// Redirect to home if no/invalid Twitter name
if (!isset($_POST['twitter_name'])):
	die('No username');
	//http_redirect('localhost/mailhawk/index.php');
else:
  $twitter_user = $_POST['twitter_name'];
  if (!validate_twitter_username($twitter_user)):
    die('Invalid Twitter username '. $twitter_user . '!');
  endif;
endif;
	
$db = new mysqli('localhost', 'root', 't3rr0r', 'emails');
if($db->connect_errno > 0):
    die('Unable to connect to database [' . $db->connect_error . ']');
endif;
	
$rand = new RandID($db);
$id = $rand->getRandID();

$datetime = new DateTime();
$datetime->add(new DateInterval('P7D'));
$expire = $datetime->format('Y-m-d H:i:s');

$sql = <<<SQL
	INSERT INTO `active`(`id`, `twitter_user`, `expire`)
	VALUES ('$id', '$twitter_user', '$expire') 
SQL;

if(!$result = $db->query($sql)):
    die('There was an error running the query [' . $db->error . ']');
endif;
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Address Created! -- MailHawk</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <link rel="stylesheet" href="css/styles.css?v=1.0">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
<?php	echo 'Success! Your temporary e-mail address is ' . $id . '@mailhawk.com' ?> 
  <script src="js/scripts.js"></script>
</body>
</html>