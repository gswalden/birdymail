<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Creator_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function insert_user($data)
    {
    	$this->db->insert('users', $data);
    }
}