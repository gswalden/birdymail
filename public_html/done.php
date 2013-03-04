<?php
// SHOULD WE ALLOW >1 ACCOUNT PER TWITTER NAME???

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

require_once('/home/birdymai/resources/library/ValidTwitter.class.php');

// Redirect to home if no/invalid Twitter name
if (!isset($_POST['twitter_name'])):
	header('Location: http://www.birdymail.me/');
else:
  $twitter_user = $_POST['twitter_name'];
  if (strcmp($twitter_user[0], '@') == 0): // Check if user entered the '@' symbol in their twiiter username
    $twitter_user = substr($twitter_user, 1);
  endif;
  if (!validate_twitter_username($twitter_user)): // pulls data from Twitter API to validate username
    die('Invalid Twitter username '. $twitter_user . '!');
  endif;
endif;

// Connect to DB
require_once('/home/birdymai/resources/mysql_login.php');

// Create id
require_once('/home/birdymai/resources/library/RandID.class.php');	
$rand = new RandID($db);
$id = $rand->getRandID();

// Get current time +seven days
$datetime = new DateTime();
$created = $datetime->format('Y-m-d H:i:s');
$datetime->add(new DateInterval('P7D'));
$expire = $datetime->format('Y-m-d H:i:s');

try {
    $stmt = $db->prepare('INSERT INTO users (id, twitter_user, created, expire) VALUES (:id, :twitter_user, :created, :expire)');
    $stmt->execute(array(':id' => $id, 
               ':twitter_user' => $twitter_user, 
                    ':created' => $created,
                     ':expire' => $expire));
} catch(PDOException $ex) {
    echo 'An Error occured!' . $ex->getMessage();
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Address Created! -- BirdyMail</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <link rel="stylesheet" href="css/styles.css?v=1.0">
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
<?php	echo 'Success! Your temporary e-mail address is ' . $id . '@birdymail.me' ?> 
  <script src="js/scripts.js"></script>
</body>
</html>