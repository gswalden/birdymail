<?php
class Tweet {

	const viewerURL = 'http://www.studiomimo.com/mailhawk/viewer.php?id=';
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
			$response = $this->connection->response['response'];
			$this->urlLen = intval(substr($response, strpos($response, 'short_url_length') + 24, 2));
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
		echo strlen($this->twitterUser);
		echo strlen(self::ygm);
		echo $this->urlLen;
		$charCount = 140 - (1 + strlen($this->twitterUser) + 1 + strlen(self::ygm) + 1 + ($this->urlLen - 1));
		echo $charCount;
		if (strlen($subject) > $charCount):
			echo $subject;
			$subject = substr($subject, 0, $charCount - 3) . '…';
			echo $subject;
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