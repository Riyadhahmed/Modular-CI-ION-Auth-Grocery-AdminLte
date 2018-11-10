<?php


class Db_util_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_admin_profile()
    {
        $query = $this->db->select('*')
            ->from('admin')
            ->get();
        $result = $query->result_array();
        return $result;

    }
}