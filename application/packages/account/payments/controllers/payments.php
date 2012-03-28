<?php

class Payments_Controller extends Application\Core\App_Controller {

	function index()
	{
		$users = Model::factory('admin/settings/settings_model')->find();

		debug($users->result());
	}

} 