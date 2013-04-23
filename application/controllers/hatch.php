<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hatch extends CI_Controller {

	public function index($id)
	{
		// Load model
		$this->load->model("Hatcher", "", TRUE);

		// Validate user $id
		$this->_isFalse($this->Hatcher->isUser($id), $id);

		// Sets last accessed
		$this->Hatcher->setAccess($id);

		// Gets all e-mails with $id
		$data["query"] = $this->Hatcher->getEmails($id);		
		
		// Gets time from now till $id expiration
		$expire = $this->Hatcher->getExpire($id);
		$expire = $expire->result();
		$expdatetime = new DateTime($expire[0]->expire);
		unset($expire);
		$data["expire"] = new DateTime();
		$interval = $data["expire"]->diff($expdatetime);

		$doPlural = function($nb,$str){return $nb>1?$str."s":$str;}; // adds plurals 
	    
	    if($interval->y !== 0): 
	        $format = "in %y ".$doPlural($interval->y, "year");  
	    elseif($interval->m !== 0): 
	        $format = "in %m ".$doPlural($interval->m, "month");  
	    elseif($interval->d !== 0): 
	        $format = "in %d ".$doPlural($interval->d, "day");  
	    elseif($interval->h !== 0): 
	        $format = "in %h ".$doPlural($interval->h, "hour"); 
	    else:
	    	$format = "within the hour";
	    endif;

	    $data["expire"] = $interval->format($format);
	
		// Load template
		$this->load->view("hatch", $data);
	}
	private function _isFalse($stmt, $id)
	{
		if ($stmt === false):
			$this->load->helper("url");
			redirect("/rotten/$id");
		endif;
	}
}