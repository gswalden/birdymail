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

		// Gets all e-mails with $id
		$data['query'] = $this->Hatcher->getEmails($id);		
		
		if ($data['query']->num_rows() < 1):
			$this->load->helper('url');
			redirect('/');
		endif;

		// Gets $id expiration
		$expire = $this->Hatcher->getExpire($id);
		$expire = $expire->result();
		$expdatetime = new DateTime($expire[0]->expire);
		unset($expire);
		$data['expire'] = new DateTime();
		$interval = $data['expire']->diff($expdatetime);

		$doPlural = function($nb,$str){return $nb>1?$str.'s':$str;}; // adds plurals 
	    
	    if($interval->y !== 0): 
	        $format = "in %y ".$doPlural($interval->y, "year");  
	    elseif($interval->m !== 0): 
	        $format = "in %m ".$doPlural($interval->m, "month");  
	    elseif($interval->d !== 0): 
	        $format = "in %d ".$doPlural($interval->d, "day");  
	    elseif($interval->h !== 0): 
	        $format = "in %h ".$doPlural($interval->h, "hour"); 
	    else:
	    	$format = 'within the hour';
	    endif;
	    /* 
	    if($interval->i !== 0) { 
	        $format[] = "%i ".$doPlural($interval->i, "minute"); 
	    } 
	    if($interval->s !== 0) { 
	        if(!count($format)) { 
	            return "less than a minute ago"; 
	        } else { 
	            $format[] = "%s ".$doPlural($interval->s, "second"); 
	        } 
	    }*/

	    $data['expire'] = $interval->format($format);
	
		$this->load->view('hatch', $data);
	}
}