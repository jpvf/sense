<?php

class Profile extends Application\Core\App_Controller {

	function index()
	{
		$users = Model::factory('account_model')->find();

		debug($users->result());
		$this->load->view('index');	
	}	

}