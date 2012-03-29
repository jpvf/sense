<?php

namespace Application\Helpers;

class Routes {
	
	private static $_routes = array();

	public static function post($route = '', $callback = null)
	{
		static::_register($route, $callback);
	}

	public static function get($route = '', $callback = null)
	{
		static::_register($route, $callback);
	}

	private static function _register($route = '', $callback = null)
	{
		static::$_routes[trim(trim($route), '/')] = $callback; 
	}

	public static function run($uri = '')
	{
		$uri = trim($uri, '/');

		foreach (static::$_routes as $route => $callback)
		{
			if ( ! preg_match('#^'.$route.'$#', $uri))
			{
				continue;	
			}

			preg_match('#^'.$route.'$#', $uri, $variables);

			call_user_func_array(static::$_routes[$route], array_slice($variables, 1));
		}
	}


}