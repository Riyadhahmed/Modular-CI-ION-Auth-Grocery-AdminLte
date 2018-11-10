<?php

class Admin_Base_Controller extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->library( array( 'ion_auth' ) );
		
		if ( ! $this->ion_auth->logged_in() ) {
			redirect( '/auth', 'refresh' );
		}
	}

	protected function title( $title ) {
		$this->data['title'] = trim( $title );
	}
}