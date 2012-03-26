<?php

namespace Sense\Core;

class Model {
    
       
    /**
    * @var $db
    */
    public $db;
    
    /**
     * @var $_models contiene los modelos cargados mediante el factory
     */
    private static $_models = array();

	function __construct()
	{
		//show(__CLASS__ . ' iniciada <br>');
		$this->db = \Sense\Database\db::getInstance();
	}

	/**
	 * El modelo arranca sin un solo objeto cargado, y con esta funcion se cargan de acuerdo a la necesidad.
	 * 
	 * @param $key
	 * @return object $base->$key
	 */
	function __get($key)
	{
		$base = Controller::getInstance();
		$this->$key = $base->$key;
		return $base->$key;
	}
	
	/**
	 * Carga los modelos y devuelve el objeto creado
	 * 
	 * @param string $model
	 * @return object $object Instancia del objeto del modelo
	 */
    static function factory($model = '')
    {
        if (empty($model))
        {
            return FALSE;
        }
        
        $model = strtolower($model);
        
        if (key_exists($model, self::$_models))
        {
            return self::$_models[$model];
        }
        
        Loader::getInstance()->model($model, $model, FALSE);
        
        if (strpos($model, '/') !== FALSE)
		{
		    $model = explode('/', $model);
		    $model = end($model);
		}
		
        $object =  new $model;
        
        self::$_models[$model] = $object;
        
        return $object;
    }
    
		
}