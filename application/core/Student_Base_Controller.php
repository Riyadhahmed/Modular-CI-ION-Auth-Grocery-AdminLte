<?php

class Student_Base_Controller extends MY_Controller {

	private $login;
	public  $dashboard;
	public  $logout_url;

	function __construct() {
		parent::__construct();
		$this->login      = 'welcome/login';
		$this->dashboard  = 'dashboard';
		$this->logout_url = 'welcome/login/logout';
		$this->is_logged_in();
	}

	protected function is_logged_in() {

		if ( ! $this->session->userdata( 'student_logged_in' ) ) {
			redirect( 'auth/student_login/login' );
		}
	}

	protected function title( $title ) {
		$this->data['title'] = trim( $title );
	}
}