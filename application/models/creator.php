<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Creator extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function insertUser($data)
    {
    	$this->db->insert('users', $data);
        $this->db->set('num_value', 'num_value+1', FALSE)->set('updated', 'NOW()', FALSE)->update('config', null, array('name'=>'total_eggs'));
    }

    function isUser($id)
    {
        $this->db->from('users')->where('id', $id);
        if ($this->db->count_all_results() > 0) return TRUE;
        return FALSE;
    }    

    function getUser($id)
    {
    	return $this->db->get_where('users', array('id' => $id));
    }
}