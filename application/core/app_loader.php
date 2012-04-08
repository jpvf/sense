<?php

namespace Application\Core;

class App_Loader extends \Sense\Core\Loader {

	private static $instance;

	/**
	 * Singleton para usar el objeto del loader.
	 * @return objeto
	 */
	public static function getInstance() 
    {        
        if ( ! self::$instance) 
        { 
            self::$instance = new App_Loader(); 
        }         
        return self::$instance; 
    }

    function test(){}


}