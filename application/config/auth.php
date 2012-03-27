<?php

/**
 * Auth Bundle configuration, set up one or more authentication options
 */

return array(

	/*
	|--------------------------------------------------------------------------
	| Admin Configuration
	|--------------------------------------------------------------------------
	|
	| Admin package authentication configuration
	|
	*/

	'admin' => array(

	   /*
		|--------------------------------------------------------------------------
		| Url to redirect after login
		|--------------------------------------------------------------------------
		|
		| You can leave this empty and it will redirect to admin/
		|
		*/

		'after_login' => 'admin/dashboard',

		/*
		|--------------------------------------------------------------------------
		| Url to redirect after logout
		|--------------------------------------------------------------------------
		|
		| You can leave this empty and it will redirect to /
		|
		*/

		'after_logout' => 'login',

		/*
		|--------------------------------------------------------------------------
		| Users table to use
		|--------------------------------------------------------------------------
		|
		| As you may have a table for customers and another for users of the system
		| this variable will become handy for that, just set the name of the table
		| to use. By default, if you leave this field empty, it will try to use
		| `users`.
		|
		*/

		'table' => 'users',

		/*
		|--------------------------------------------------------------------------
		| Username field to use
		|--------------------------------------------------------------------------
		|
		| Which table field to use as username. By default, if you leave this field 
		| empty, it will try to use `username`
		|
		*/

		'username_field' => 'username',
	)

);

