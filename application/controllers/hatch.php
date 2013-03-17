<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hatch extends CI_Controller {

	public function index($id)
	{
		// Load model
		$this->load->model('Hatcher', '', TRUE);

		if (!$this->Hatcher->isUser($id)):
			$this->load->helper('url');
			redirect('/');
		endif;

		$data['query'] = $this->Hatcher->getEmails($id);
		
		if ($data['query']->num_rows() < 1):
			$this->load->helper('url');
			redirect('/');
		endif;

		$this->load->view('hatch', $data);
	}
}