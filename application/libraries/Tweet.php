<?php
class Tweet {

	const viewerURL = 'http://www.birdymail.me/view/';
	const ygm = 'Fresh egg: ';

	private $twitterUser;
	private $twitterMessage;
	private $connection;
	private $urlLen;

	public function __construct()
	{
		if (defined('BASEPATH')):
			$CI =& get_instance(); // sets CI as refernce to framework (in order to use)

			// Load the app's OAuth tokens into memory
			$CI->config->load('app_tokens');

			// Load the tmhOAuth library
			$CI->load->library('TMHOAuth');

			$config['consumer_key'] = $CI->config->item('consumer_key');
			$config['consumer_secret'] = $CI->config->item('consumer_secret');
			$config['user_token'] = $CI->config->item('user_token');
			$config['user_secret'] = $CI->config->item('user_secret');
		else:
			require_once '/home/birdymai/application/config/app_tokens.php';
			require_once '/home/birdymai/application/libraries/TMHOAuth.php';
		endif;

		$this->connection = new TMHOAuth(array(
			'consumer_key'    => $config['consumer_key'],
			'consumer_secret' => $config['consumer_secret'],
			'user_token'      => $config['user_token'],
			'user_secret'     => $config['user_secret']
			));
		$code = $this->connection->request('GET', $this->connection->url('1.1/help/configuration.json'));
		if ($code == 200):
			$response_data = json_decode($this->connection->response['response'],true);
			$this->urlLen = $response_data['short_url_length'];
		else:
			mail('mimo@birdymail.me', 'Error in Tweet.class', 'in construct, code: ' . $code);
		endif;
	}

	public function getUser()
	{
		return $this->twitterUser;
	}

	public function setUser($user)
	{
		if (strcmp($user[0], '@') == 0): // Check if user entered the '@' symbol in their twiiter username
		    $user = substr($user, 1);
		endif;
		$this->twitterUser = $user;
		if (!$this->validateTwitterUsername()):
			$this->twitterUser = FALSE;
  		endif;
	}

	public function setMessage($subject)
	{
		$charCount = 140 - (1 + strlen($this->twitterUser) + 1 + strlen(self::ygm) + 1 + $this->urlLen);
		if (strlen($subject) > $charCount):
			$subject = substr($subject, 0, $charCount - 3) . '…';
		endif;
		$this->twitterMessage = '@' . $this->twitterUser . ' ' . self::ygm . $subject . ' ' . self::viewerURL;
	}

	public function post($id)
	{
		$code = $this->connection->request('POST', 
			$this->connection->url('1.1/statuses/update'), 
			array('status' => $this->twitterMessage . $id));
		if ($code != 200):
			mail('mimo@birdymail.me', 'Error in Tweet.class', 'in post, code: ' . $code);
		endif;
	}

	private function validateTwitterUsername()
	{
		// Get account info
		$this->connection->request('GET', $this->connection->url('1.1/users/show'), array(
		 		'screen_name' => "$this->twitterUser"
			));

		// Get the HTTP response code for the API request
		if ($this->connection->response['code'] == 200):
			return true;
		else:
			return false;
		endif;
	}
}