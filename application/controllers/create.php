<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Create extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://birdmail.me/index.php/create
	 *	- or -  
	 * 		http://birdmail.me/index.php/create/index
	 *	- or -
	 *		http://birdmail.me/create
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/create/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// Redirect to home if no Twitter name in POST data
		$user = $this->input->post("twitter_name", TRUE); // Grabs POST data, FALSE if none. Checks for XSS
		$this->_isFalse($user);

		// Redirects if Twitter username invalid
		$twitter_user = $this->_setUser($user);
		$this->_isFalse($twitter_user, $user);

		// Load model
		$this->load->model("Creator", "", TRUE);

		// Create id
		$id = $this->_createID();

		// Get current time & current time + 21 days
		$datetime = new DateTime();
		$created = $datetime->format("Y-m-d H:i:s");
		$datetime->add(new DateInterval("P21D"));
		$expire = $datetime->format("Y-m-d H:i:s");
		unset($datetime);

		$data = array(
			   "id" => $id,
			   "twitter_user" => $twitter_user,
			   "created" => $created,
			   "expire" => $expire
			);
		$private = $this->input->post("private", TRUE);
		if ($private)
			$data["private"] = 1;

		$this->Creator->insertUser($data);
		
		$this->load->view("create", $data);
	}
	private function _createID()
	{
		$this->load->library("RandID");
		$rand = new RandID();
		return $rand->getRandID();
	}
	private function _isFalse($twitter_user, $user=null)
	{
		if ($twitter_user === false):
			if ($user !== null)
				$user = "/" . $user;
			$this->load->helper("url");
			redirect("/rotten" . $user);
		endif;
	}
	private function _setUser($user)
	{
		$this->load->library("Tweet");
		$twitter_user = new Tweet();
		$twitter_user->setUser($user); // pulls data from Twitter API to validate username
		return $twitter_user->getUser(); // FALSE if invalid
	}
}

/* End of file create.php */
/* Location: ./application/controllers/create.php */