<?php
/*
 * Archivo de configuracion general del sistema.* 
 */ 

return array(

	/*
	|--------------------------------------------------------------------------
	| Sets the actual url
	|--------------------------------------------------------------------------
	|
	| This variable will set the base url for the entire system for anchors, 
	| forms, redirects etc. It will only find the actual url as in 
	| http://www.example.com/.
	|
	*/

	'base_url' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http")."://".$_SERVER['HTTP_HOST'].'/',

	/*
	|--------------------------------------------------------------------------
	| Application Index
	|--------------------------------------------------------------------------
	|
	| If you are including the "index.php" in your URLs, you can ignore this.
	| However, if you are using mod_rewrite to get cleaner URLs, just set
	| this option to an empty string.
	|
	*/

	'index_page' => "index.php",

	/*
	|--------------------------------------------------------------------------
	| System email
	|--------------------------------------------------------------------------
	|
	| The system email to send errors to, if in development all the emails
	| will be sent to this one
	|
	*/

	'admin_email' => "developers@sense.com",

	/*
	|--------------------------------------------------------------------------
	| Encoding Key
	|--------------------------------------------------------------------------
	|
	| This will be the encoding key for the whole application so you can use it
	| encoding: passwords, sessions and pretty much everything you need to, 
	| you can encode any string using the helper function salt_it($string).
	|
	*/

	'salt' => "SE2579NS346E8S3A5L6T",

	/*
	|--------------------------------------------------------------------------
	| Clean XSS Globally
	|--------------------------------------------------------------------------
	|
	| If set to true the system will clean up all the globals variables such as
	| GET, POST, SERVER from xss, it will hit performance.
	|
	*/
	
	'sanitize_globals'   => false,
	
	/*
	|--------------------------------------------------------------------------
	| System's default lang
	|--------------------------------------------------------------------------
	|
	| This will be the default language used to load language files
	|
	*/
	
	'default_lang'		  => 'es_LA',

	/*
	|--------------------------------------------------------------------------
	| CSRF token
	|--------------------------------------------------------------------------
	|
	| This is the token used in csrf protection
	|
	*/
	
	'token'			  => md5(sha1(uniqid(rand()))),

	/*
	|--------------------------------------------------------------------------
	| Session expire time
	|--------------------------------------------------------------------------
	|
	| This will be the session expire time in minutes
	|
	*/
	
	'session_time'		  => 30,
);