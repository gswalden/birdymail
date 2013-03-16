<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class View extends CI_Controller {

	public function index($id)
	{
		// Load model
		$this->load->model('Viewer', '', TRUE);

		if (!$this->Viewer->isUser($id)):
			$this->load->helper('url');
			redirect('/');
		endif;

		$data['query'] = $this->Viewer->getEmails($id);
		
		if ($data['query']->num_rows() < 1):
			$this->load->helper('url');
			redirect('/');
		endif;

		$this->load->view('view', $data);
	}
}