<?php
class Tweet {

	const viewerURL = 'http://www.birdymail.me/hatch/';
	const ygm = 'Fresh egg: ';

	private $twitterUser;
	private $twitterMessage;
	private $connection;
	private $urlLen;

	public function __construct()
	{
		if (defined('BASEPATH')): // if running through CI website
			$CI =& get_instance(); // sets CI as refernce to framework (in order to use)

			// Load the app's OAuth tokens into memory
			$CI->config->load('app_tokens');

			// Load the tmhOAuth library
			$CI->load->library('TMHOAuth');

			$config['consumer_key'] = $CI->config->item('consumer_key');
			$config['consumer_secret'] = $CI->config->item('consumer_secret');
			$config['user_token'] = $CI->config->item('user_token');
			$config['user_secret'] = $CI->config->item('user_secret');
		else: // if running through mailparser (parser/mail.php)
			require_once '/home/birdymai/application/config/app_tokens.php';
			require_once '/home/birdymai/application/libraries/TMHOAuth.php';
		endif;

		$this->connection = new TMHOAuth(array(
			'consumer_key'    => $config['consumer_key'],
			'consumer_secret' => $config['consumer_secret'],
			'user_token'      => $config['user_token'],
			'user_secret'     => $config['user_secret']
			));
	}

	public function getMentions($id)
	{
		$code = $this->connection->request(
			'GET', 
			$this->connection->url('1.1/statuses/mentions_timeline.json'), 
			array('count' => 200,
			   'since_id' => $id));
		if ($code == 200)
			return json_decode($this->connection->response['response'],true);
		mail('mimo@birdymail.me', 'Error in Tweet.class', 'in ' . __FUNCTION__ . ', code: ' . $code);
	}
	
	public function getTweet($id) // NOT FINISHED
	{
		$code = $this->connection->request('GET', 
			$this->connection->url('1.1/statuses/show.json'), 
			array('id' => $id));
		if ($code == 200)
			return $response_data = json_decode($this->connection->response['response'],true);
		mail('mimo@birdymail.me', 'Error in Tweet.class', 'in ' . __FUNCTION__ . ', code: ' . $code . 'for id' . $id);
	}

	public function getUser()
	{
		return $this->twitterUser;
	}

	public function setUser($user)
	{
		$user = trim($user);
		// Check if user entered the '@' symbol in their twiiter username
		if (strcmp($user[0], '@') == 0) 
			$user = substr($user, 1);
		$this->twitterUser = $user;
		if (!$this->_validateTwitterUsername()) 
			$this->twitterUser = FALSE;
	}

	public function setMessage($subject)
	{
		// Connect to DB
		require '/home/birdymai/application/config/mysql_login.php';
		try {
		  $db = new PDO("mysql:dbname=$mysql_db;host=localhost", $mysql_username, $mysql_password);
		  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $ex) {
		  mail('mimo@birdymail.me', 'DB Error in Tweet', $ex->getMessage());
		}
		try {
		  $stmt = $db->prepare('SELECT num_value FROM config WHERE name=:url_length');
		  $stmt->execute(array(':url_length' => 'url_length'));
		  $row = $stmt->fetch();
		  $this->urlLen = $row[0];
		} catch(PDOException $ex) {
		  mail('mimo@birdymail.me', 'DB Error in Tweet', $ex->getMessage());
		}
		
		$charCount = 140 - (1 + strlen($this->twitterUser) + 1 + strlen(self::ygm) + 1 + $this->urlLen);
		if (strlen($subject) > $charCount)	
			$subject = substr($subject, 0, $charCount - 3) . '…';
		$this->twitterMessage = '@' . $this->twitterUser . ' ' . self::ygm . $subject . ' ' . self::viewerURL;
	}

	public function sendStopMessage($user, $id)
	{
		$code = $this->connection->request('POST', 
			$this->connection->url('1.1/statuses/update'), 
			array('status' => '@' . $user . ' We cracked your egg(s). Thanks!',
    				'in_reply_to_status_id' => $id));
		if ($code != 200)
			mail('mimo@birdymail.me', 'Error in Tweet.class', 'in ' . __FUNCTION__ . ', code: ' . $code);
	}

	public function sendExtendMessage($user, $id)
	{
		$code = $this->connection->request('POST', 
			$this->connection->url('1.1/statuses/update'), 
			array('status' => '@' . $user . ' Your egg\'s life was extended one week. Thanks!',
    				'in_reply_to_status_id' => $id));
		if ($code != 200)
			mail('mimo@birdymail.me', 'Error in Tweet.class', 'in ' . __FUNCTION__ . ', code: ' . $code);
	}

	public function post($id)
	{
		$code = $this->connection->request('POST', 
			$this->connection->url('1.1/statuses/update'), 
			array('status' => $this->twitterMessage . $id));
		if ($code != 200)
			mail('mimo@birdymail.me', 'Error in Tweet.class', 'in ' . __FUNCTION__ . ', code: ' . $code);
	}

	private function _validateTwitterUsername()
	{
		// Get account info
		$this->connection->request('GET', $this->connection->url('1.1/users/show'), array(
		 		'screen_name' => "$this->twitterUser"
			));

		// Get the HTTP response code for the API request
		if ($this->connection->response['code'] == 200)
			return true;
		else
			return false;
	}

	public function urlLength()
	{	// runs twice a day
		$code = $this->connection->request('GET', $this->connection->url('1.1/help/configuration.json'));
		if ($code == 200):
			$response_data = json_decode($this->connection->response['response'],true);
			return $response_data['short_url_length'];
		else:
			mail('mimo@birdymail.me', 'Error in Tweet.class', 'in ' . __FUNCTION__ . ', code: ' . $code);
			return false;
		endif;
	}
}