<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class M_admin_dashboard extends CI_Model {

	private $table = 'users';
	public $user_name = 'user_name';
	public $user_email = 'user_email';
	public $user_id = 'user_id';
	public $role_id = 'role_id';
	public $company_id = 'company_id';

	function __construct() {
		parent::__construct();
	}

	function get_admin_profile($id) {
		$query  = $this->db->select( '*' )
		                   ->from( $this->table )
		                   ->where( $this->user_id, $id )
		                   ->get();
		$result = $query->result_array();

		return $result;

	}

	public function admin_profile_update_process( $change_password, $id ) {
		$result = $this->db->where( 'user_id', $id )->update( 'users', $change_password );
		if ( $result ) {
			return true;
		} else {
			return false;
		}
	}

	public function get_all_group()
	{
		$this->db->select('*')
		         ->from('tbl_group');
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}

	public function get_all_contact()
	{
		$this->db->select('*')
		         ->from('contact_inf');
		$query = $this->db->get();
		if ($query->num_rows() != 0) {
			return $query->result_array();
		} else {
			return false;
		}
	}
} 