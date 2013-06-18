<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "libraries/REST_Controller.php";

class Create extends REST_Controller {

	public function new_post()
	{
		$user = $this->input->post("twitter_name", TRUE); // Grabs POST data, FALSE if none. Checks for XSS

		$twitter_user = $this->_setUser($user);
		if ($twitter_user === FALSE)
			$this->_send_response();

		// Get other post vars
		$exp = $this->input->post("expire_days", TRUE);
		$dm = $this->input->post("direct_message", TRUE);

		// Load model
		$this->load->model("Creator", "", TRUE);

		// Create id
		$id = $this->_createID();

		// Get current time & current time + $dm days
		$datetime = new DateTime();
		$created = $datetime->format("Y-m-d H:i:s");
		$days = "P" . $exp . "D"; // format P#D
		$datetime->add(new DateInterval($days));
		$expire = $datetime->format("Y-m-d H:i:s");
		unset($datetime);

		$data = array(
			   "id" => $id,
			   "twitter_user" => $twitter_user,
			   "created" => $created,
			   "expire" => $expire,
			   "private" => $dm
			);
		$this->Creator->insertUser($data);

		$res["id"] = $data["id"];
		$this->_send_response($res);
	}
	
	private function _createID()
	{
		$this->load->library("RandID");
		$rand = new RandID();
		return $rand->getRandID();
	}

	private function _setUser($user)
	{
		$this->load->library("Tweet");
		$twitter_user = new Tweet();
		$twitter_user->setUser($user); // pulls data from Twitter API to validate username
		return $twitter_user->getUser(); // FALSE if invalid
	}

	private function _send_response($data = NULL)
	{
		if ($data)
			$this->response($data, 200);
		else
			$this->response(NULL, 404);
	}
}

/* End of file create.php */
/* Location: ./application/controllers/create.php */