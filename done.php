<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

require_once('resources/library/ValidTwitter.php');

// Redirect to home if no/invalid Twitter name
if (!isset($_POST['twitter_name'])):
	die('No username');
	//http_redirect('localhost/mailhawk/index.php');
else:
  $twitter_user = $_POST['twitter_name'];
  if ($twitter_user[0] == '@') // Check if user entered the '@' symbol in their twiiter username
    $twitter_user = substr($twitter_user, 1);
  endif;
  if (!validate_twitter_username($twitter_user)): // pulls data from Twitter API to validate username
    die('Invalid Twitter username '. $twitter_user . '!');
  endif;
endif;

require_once('resources/mysql_login.php');
	
$db = new mysqli('localhost', $mysql_username, $mysql_password, 'emails');
if($db->connect_errno > 0):
    die('Unable to connect to database [' . $db->connect_error . ']');
endif;

require_once('resources/library/RandID.php');	
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