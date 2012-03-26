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
		if( ! is_null($error))
		{
			switch($error)
			{
				case 'model_missing':
					echo 'No model was found with the name: ' . $args . '<br />';
					break;		
				case 'controller_missing':
					echo 'No controller was found with the name: ' . $args . '<br />';
					break;
				case 'view_missing':
					echo 'No view was found with the name: ' . $args . '<br />';
					break;	
				case '404':
				    header("HTTP/1.0 404 Not Found");
				    if (function_exists('is_xhr') AND is_xhr())
				    {
				        echo '404. Página no encontrada';
				        exit;
				    }
					include(APP_PATH.'errors/404'.EXT);
					exit;
					break;

			}
		}
	
	}




}
