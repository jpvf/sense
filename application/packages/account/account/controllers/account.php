<?php

use \Application\Helpers\Routes;

class Account_Controller extends Application\Core\App_Controller {

	function index()
	{
		Session::set('admin.id', 1);
		Session::set('admin.ip_address', $this->input->ip_address());

		//Session::flash('status', 'lol');
		//Session::destroy();
		debug(Session::get('admin.id'));
		Session::remove('admin.ip_address');
		debug(Session::all());
		$model = Model::factory('account_model');
		echo Session::id();
		echo br(2);

		$this->template;
	}

}



/*
function select()
{
	$fields = func_get_args();

	debug($fields);	
}

Routes::get('/, home', function()
{
	Views::factory('index', array())->render();
});

Routes::post('hsome', function($pagina = 'index', $o = 'lll')
{
	echo 'Pagina : '.$pagina;
});

Routes::get('/admin', function()
{
	echo 'Admin';
});

Routes::get('/admin/users/([a-zA-Z0-9_-]+)/details[\/|]([a-zA-Z0-9_-]+)', function($user, $profile)
{
	echo 'User - '.$user.' : Profile - '.$profile ;
});

Routes::get('/admin/users/create', function()
{
	echo 'Create User';
});

Routes::get('account/account(/index|)', function()
{
	echo 'Account';
});


class Views {

	function __construct($view = '', $data = array())
	{
		Loader::getInstance()->view($view, $data);
	}

	function render(){}

	public static function factory($view = '', $data = array())
	{
		return new Views($view, $data);
	}

}*/