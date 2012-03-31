<?php 

namespace Sense\Core;

class Error{

	private static $instance; 
	
	function __construct()
	{
		show(__CLASS__ . ' iniciada <br>');
	}

	public static function getInstance() 
    { 
        if ( ! self::$instance) 
        { 
            self::$instance = new Error(); 
        } 
        return self::$instance; 
    }
    
	public function trigger_error($error = NULL, $args = null)
	{
		if(is_null($error))
		{
			return;
		}

		switch($error)
		{
			case '404':
			    header("HTTP/1.0 404 Not Found");
			    
			    if (function_exists('is_xhr') AND is_xhr())
			    {
			    	header('Cache-Control: no-cache, must-revalidate');
					header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			        exit;
			    }

				include(APP_PATH.'errors/404'.EXT);
				exit;
				break;

		}
	
	}




}
