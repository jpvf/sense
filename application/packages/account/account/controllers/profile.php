<?php

class Profile_Controller extends Application\Core\App_Controller {

	function index()
	{
		$this->template->set_template('base');

		$this->template->set_content('main', 'index')
					   ->render();
	}	

}