<?php
class Tweet {

	const viewerURL = 'http://www.birdymail.me/viewer.php?id=';
	const ygm = 'You\'ve got mail: ';

	private $twitterUser;
	private $twitterMessage;
	private $connection;
	private $urlLen;

	public function __construct()
	{
		// Load the app's OAuth tokens into memory
		require_once 'oauth/app_tokens.php';

		// Load the tmhOAuth library
		require_once 'oauth/tmhOAuth.php';

		$this->connection = new tmhOAuth(array(
			'consumer_key'    => $consumer_key,
			'consumer_secret' => $consumer_secret,
			'user_token'      => $user_token,
			'user_secret'     => $user_secret
			));
		$code = $this->connection->request('GET', $this->connection->url('1.1/help/configuration.json'));
		if ($code == 200):
			$response_data = json_decode($this->connection->response['response'],true);
			$this->urlLen = $response_data['short_url_length'];
		else:
			print "Error: $code";
		endif;
	}
	public function setUser($user)
	{
		$this->twitterUser = $user;
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
		echo $this->twitterMessage;
		$code = $this->connection->request('POST', 
			$this->connection->url('1.1/statuses/update'), 
			array('status' => $this->twitterMessage . $id));
		if ($code == 200):
			print "Tweet sent";
		else:
			print "Error: $code";
		endif;
	}
}