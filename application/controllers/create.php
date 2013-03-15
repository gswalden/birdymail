<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Create extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://birdmail.me/index.php/create
	 *	- or -  
	 * 		http://birdmail.me/index.php/create/index
	 *	- or -
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/create/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		// Redirect to home if no/invalid Twitter name
		$twitter_user = $this->input->post('twitter_name', TRUE); // Grabs POST data, FALSE if none. Checks for XSS
		if (!$twitter_user):
			$this->load->helper('url');
			redirect('/');
		else:
			$twitter_user = $this->setUser($twitter_user);
			if (!$twitter_user):
				// redirects if Twitter username invalid
				// ADD ERROR MSG
				$this->load->helper('url');
				redirect('/');
			endif;	
		endif;

		// Create id
		$id = $this->createID();

		// Get current time + 7 days
		$datetime = new DateTime();
		$created = $datetime->format('Y-m-d H:i:s');
		$datetime->add(new DateInterval('P7D'));
		$expire = $datetime->format('Y-m-d H:i:s');
		unset($datetime);

		$data = array(
			   'id' => $id,
			   'twitter_user' => $twitter_user,
			   'created' => $created,
			   'expire' => $expire
			);

		$this->load->model('Creator', '', TRUE);
		$this->Creator->insert_user($data);
		
		$this->load->view('create', $data);
	}
	private function createID()
	{
		$this->load->library('RandID');
		$rand = new RandID();
		return $rand->getRandID();
	}
	private function setUser($user)
	{
		$this->load->library('Tweet');
		$twitter_user = new Tweet();
		$twitter_user->setUser($user); // pulls data from Twitter API to validate username
		return $twitter_user->getUser(); // FALSE if invalid
	}
}

/* End of file create.php */
/* Location: ./application/controllers/create.php */