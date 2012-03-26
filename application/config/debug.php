<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Application mode
	|--------------------------------------------------------------------------
	|
	| If you are in development stage, you should set this variable to true, 
	| it will send emails to a predetermined email address and can save you
	| a lot of headaches.
	|
	*/

	'devel' => true,

	/*
	|--------------------------------------------------------------------------
	| Show php errors
	|--------------------------------------------------------------------------
	|
	| If you want to show php at any time, set this variable to true, however
	| if you are in production stage it is recommended to set it to false 
	| to avoid showing any errors.
	|
	*/

	'debug' => true

	/*
	|--------------------------------------------------------------------------
	| Show database errors
	|--------------------------------------------------------------------------
	|
	| If you want to show database errors at any time, set this variable 
	| to true, however if you are in production stage it is recommended to 
	| set it to false to avoid showing any errors.
	|
	*/
	
	'dbdebug' => true,

	/*
	|--------------------------------------------------------------------------
	| Mail Database errors
	|--------------------------------------------------------------------------
	|
	| If you set this variable to true the system will send to the config
	| 'admin_email' any database errors that appear.
	|
	*/

	'mail_errors' => false

);