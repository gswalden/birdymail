<?php
class Tweet {

	const viewerURL = "http://birdymail.me/hatch/";
	const ygm = "Fresh egg: ";

	private $twitterUser;
	private $twitterMessage;
	private $type;
	private $connection;
	private $urlLen;

	public function __construct()
	{
		if (defined("BASEPATH")): // if running through CI website
			$CI =& get_instance(); // sets CI as refernce to framework (in order to use)

			// Load the app's OAuth tokens into memory
			$CI->config->load("app_tokens");

			// Load the tmhOAuth library
			$CI->load->library("TMHOAuth");

			$config["consumer_key"] = $CI->config->item("consumer_key");
			$config["consumer_secret"] = $CI->config->item("consumer_secret");
			$config["user_token"] = $CI->config->item("user_token");
			$config["user_secret"] = $CI->config->item("user_secret");
		else: // if running through mailparser (parser/mail.php)
			require_once "/home/birdymai/application/config/app_tokens.php";
			require_once "/home/birdymai/application/libraries/TMHOAuth.php";
		endif;

		$this->connection = new TMHOAuth(array(
			"consumer_key"    => $config["consumer_key"],
			"consumer_secret" => $config["consumer_secret"],
			"user_token"      => $config["user_token"],
			"user_secret"     => $config["user_secret"]
			));
	}

	public function getMentions($id)
	{
		$code = $this->connection->request(
			"GET", 
			$this->connection->url("1.1/statuses/mentions_timeline.json"), 
			array("count" => 200,
			   "since_id" => $id));
		if ($code == 200)
			return array_reverse(json_decode($this->connection->response["response"],true)); // Reverses to process mentions in order they were sent, not most recent first
		mail("mimo@birdymail.me", "Error in Tweet.class", "in " . __FUNCTION__ . ", code: " . $code);
	}
	
	public function getTweet($id)
	{
		$code = $this->connection->request("GET", 
			$this->connection->url("1.1/statuses/show.json"), 
			array("id" => $id));
		if ($code == 200)
			return json_decode($this->connection->response["response"],true);
		mail("mimo@birdymail.me", "Error in Tweet.class", "in " . __FUNCTION__ . ", code: " . $code . "for id" . $id);
	}

	public function getUser()
	{
		return $this->twitterUser;
	}

	public function setUser($user)
	{
		$this->twitterUser = $user;
		if ( ! $this->_validateTwitterUsername()) 
			$this->twitterUser = FALSE;
	}

	public function setEggMessage($subject, $id)
	{
		$db = $this->_connect_db();
		try {
		  $stmt = $db->prepare("SELECT num_value FROM config WHERE name=:url_length");
		  $stmt->execute(array(":url_length" => "url_length"));
		  $row = $stmt->fetch();
		  $this->urlLen = $row[0];
		} catch(PDOException $ex) {
		  mail("mimo@birdymail.me", "DB Error in Tweet: " . __FUNCTION__, $ex->getMessage());
		}
		
		$charCount = 140 - (1 + strlen($this->twitterUser) + 1 + strlen(self::ygm) + 1 + $this->urlLen);
		if (strlen($subject) > $charCount)	
			$subject = substr($subject, 0, $charCount - 3) . "…";
		$this->twitterMessage = array(
			"status" => ("@" . $this->twitterUser . " " . self::ygm . $subject . " " . self::viewerURL . $id . ".egg"));
		$this->type = "egg";
	}

	public function setMessage($msg)
	{
		$this->twitterMessage = $msg;
	}

	public function setStopMessage($user, $id)
	{
		$this->twitterMessage = array(
			"status" => ("@" . $user . " We cracked your egg(s). Thanks!"),
			"in_reply_to_status_id" => $id);
		$this->type = "stop";
	}

	public function setExtendMessage($user, $id)
	{
		$this->twitterMessage = array(
			"status" => ("@" . $user . " Your egg\"s life was extended one week. Thanks!"),
			"in_reply_to_status_id" => $id);
		$this->type = "extend";
	}

	public function setReplyMessage($msg, $id)
	{
		$this->twitterMessage = array(
			"status" => $msg,
			"in_reply_to_status_id" => $id);
	}

	public function post($skip_queue = FALSE)
	{
		if ( ! $skip_queue):
			$db = $this->_connect_db();
			try {
			  $stmt = $db->prepare("SELECT num_value FROM config WHERE name=:tweets_today");
			  $stmt->execute(array(":tweets_today" => "tweets_today"));
			  $row = $stmt->fetch();
			} catch(PDOException $ex) {
			  mail("mimo@birdymail.me", "DB Error in Tweet: " . __FUNCTION__, $ex->getMessage());
			}
			date_default_timezone_set("America/New_York");
			$seconds = time() - strtotime("today");
			// 1000 tweet/day limit, so 86.4 seconds between Tweets to never hit limit
			if (($row[0] * 86.4) > $seconds):
				try {
				  $db->prepare("INSERT INTO twitter_queue (user, message, type) VALUES
	                          			(:user, :message, :type)")
				  	 ->execute(array( ":user" => $this->twitterUser,
				  					":message" => serialize($this->twitterMessage),
				  					":type" => $this->type));
				} catch(PDOException $ex) {
				  mail("mimo@birdymail.me", "DB Error in Tweet: " . __FUNCTION__, $ex->getMessage());
				}
				return;
			endif;
		endif;
		$code = $this->connection->request("POST", 
			$this->connection->url("1.1/statuses/update"), 
			$this->twitterMessage);
		if ($code != 200)
			mail("mimo@birdymail.me", "Error in Tweet.class", "in " . __FUNCTION__ . ", code: " . $code);
		else {
			if ( ! isset($db))
				$db = $this->_connect_db();
			try {
			  $db->prepare("UPDATE config SET num_value=num_value+1 WHERE name=:tweets_today")
			     ->execute(array(":tweets_today" => "tweets_today"));
			} catch(PDOException $ex) {
			  mail("mimo@birdymail.me", "DB Error in Tweet: " . __FUNCTION__, $ex->getMessage());
			}
		}
	}

	public function urlLength()
	{	// runs twice a day
		$code = $this->connection->request("GET", $this->connection->url("1.1/help/configuration.json"));
		if ($code == 200):
			$response_data = json_decode($this->connection->response["response"],true);
			return $response_data["short_url_length"];
		else:
			mail("mimo@birdymail.me", "Error in Tweet.class", "in " . __FUNCTION__ . ", code: " . $code);
			return false;
		endif;
	}

	private function _connect_db()
	{
		require "/home/birdymai/application/config/mysql_login.php";
		try {
		  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
		  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $ex) {
		  mail("mimo@birdymail.me", "DB Error in Tweet: " . __FUNCTION__, $ex->getMessage());
		}
		return $db;
	}

	private function _validateTwitterUsername()
	{
		// Get account info
		$this->connection->request("GET", $this->connection->url("1.1/users/show"), array(
		 		"screen_name" => "$this->twitterUser"
			));

		// Get the HTTP response code for the API request
		if ($this->connection->response["code"] == 200)
			return true;
		else
			return false;
	}
}