<?php

class Account extends Application\Core\App_Controller {

	function index()
	{
		$this->session->set_flashdata('juan', 'pepe');
		debug($_SESSION);
	}

}