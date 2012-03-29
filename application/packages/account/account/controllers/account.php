<?php

use \Application\Helpers\Routes;

class Account_Controller extends Application\Core\App_Controller {

	function index()
	{
		//echo $this->uri->get_uri_string();
		//Routes::run('/admin/users/123123789/details/123123');
		Routes::run();
	}

}

Routes::get('/', function()
{
	echo 'Bienvenido Pagina principal';
});

Routes::post('home', function($pagina = 'index', $o = 'lll')
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