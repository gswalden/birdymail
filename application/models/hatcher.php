<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hatcher extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function isUser($id)
    {
        $this->db->from('users');
        $this->db->where('id', $id);
        $num_results = $this->db->count_all_results();
        if ($num_results > 0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }    

    function getEmails($id)
    {
    	return $this->db->get_where('active', array('id' => $id));
    }
}