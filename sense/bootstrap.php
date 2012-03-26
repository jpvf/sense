<?php

/**
 * Sense Version
 *
 * @var string
 *
 */
define('SENSE_VERSION', '0.1');

// Load error helpers for exceptions, errors and all of that.
include_once SYSTEM_PATH.'helpers/error'.EXT;

set_exception_handler('exceptions_handler');

if (ENVIROMENT === 'development')
{
    set_error_handler("error_handler");
}
else
{
    error_reporting(0);
}

include_once SYSTEM_PATH.'core/autoloader'.EXT;

Autoloader::init();
Autoloader::register();

Sense\Core\Config::load('config, autoload');

$router = Sense\Core\Router::getInstance();
$uri	= Sense\Core\Uri::getInstance();
$load   = Sense\Core\Loader::getInstance();
$route  = $router->set();

$load->library(Config::get('libraries'));
$load->helper(Config::get('helpers'));

include_once $route->file;

$class = ucwords($route->controller);

$error = false;

if (in_array($class, get_declared_classes()))
{
	$class = new $class;

	if (method_exists($class, $route->method) AND is_callable(array($class, $route->method)))
	{       
	    $vars = array_slice($uri->rsegments, $route->slice);

	    // Funcion que se ejecuta antes de la funcion solicitada.
	    if (method_exists($class, 'before'))
	    {
	        call_user_func_array(array($class, 'before'), $vars);    
	    }

	    call_user_func_array(array($class, $route->method), $vars);   
	    
	    // Funcion que se ejecuta despues de la funcion solicitada.
	    if (method_exists($class, 'after'))
	    {
	        call_user_func_array(array($class, 'after'), $vars);    
	    }           
	}
	else
	{
		$error = true;
	}
}
else
{
	$error = true;
}

$error AND throw_error();