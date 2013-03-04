<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
// BEGIN MailParser******************************************
require_once('resources/library/MimeMailParser.class.php');
require_once('resources/library/Tweet.class.php');

$path = 'resources/library/mail.txt';
$Parser = new MimeMailParser();
//$Parser->setStream(fopen($path));
$Parser->setPath($path);

$to = $Parser->getHeader('to');
$sender = $Parser->getHeader('from');
$subject = $Parser->getHeader('subject');
$textbody = $Parser->getMessageBody('text');
$htmlbody = $Parser->getMessageBody('html');
$attachments = $Parser->getAttachments();
// END MailParser^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

$id = substr($to, 0, strpos($to, '@'));

// BEGIN MySQL******************************************
require_once('resources/mysql_login.php');
    
// Connect to DB
try {
    $db = new PDO('mysql:dbname=emails;host=localhost', $mysql_username, $mysql_password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
    echo 'An Error occured!' . $ex->getMessage();
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Fetch Twitter user associated with e-mail account
try {
    foreach($db->query("SELECT twitter_user FROM active WHERE id = $id") as $row):
        $twitter_user = $row['twitter_user'];
    endforeach;
} catch(PDOException $ex) {
    echo 'An Error occured!' . $ex->getMessage();
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}

// Add e-mail to DB
try {
    $sth = $db->prepare('UPDATE active SET subject = :subject, 
                                      sender = :sender, 
                                    htmlbody = :htmlbody, 
                                    textbody = :textbody 
                                    WHERE id = :id');
} catch(PDOException $ex) {
    echo 'An Error occured!';
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}
try {
    $sth->execute(array(':subject' => $subject, 
                     ':sender' => $sender, 
                   ':htmlbody' => $htmlbody, 
                   ':textbody' => $textbody, 
                         ':id' => $id));
} catch(PDOException $ex) {
    echo 'An Error occured!';
    mail('mimo@birdymail.me', 'DB Error', $ex->getMessage());
}
// END MySQL^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

$tweet = new Tweet();
$tweet->setUser($twitter_user);
$tweet->setMessage($subject);
$tweet->post($id);
echo 'Done';
?>