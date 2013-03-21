<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hatcher extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function isUser($id)
    {
        $this->db->from('users')->where('id', $id);
        if ($this->db->count_all_results() > 0) 
            return TRUE;
        return FALSE;
    }    

    function getEmails($id)
    {
    	return $this->db->order_by('date','desc')->get_where('active', array('id' => $id));
    }

    function getExpire($id)
    {
        return $this->db->get_where('users', array('id' => $id));
    }

    function setAccess($id)
    {
        $this->db->set('last_access', 'NOW()', FALSE)->update('users', null, array('id'=>$id));
    }
}