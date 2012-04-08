<?php 

namespace Sense\Core;

class Loader_Exception extends \Exception{}
/**
 * @author JuanPablo
 * Clase para cargar las librerias, archivos de idioma y scripts
 */
class Loader{
	
	/**
	 * @var objeto loader, usado en el singleton
	 */
	private static $instance; 
    public $objects = array();
    public $controlador = '';
    private $_files;
		
	/**
	 * constructor de la clase
	 * @return void
	 */
	function __construct()
	{
		show(__CLASS__ . ' iniciada <br>');
	}
	
	/**
	 * Singleton para usar el objeto del loader.
	 * @return objeto
	 */
	public static function getInstance() 
    {        
        if ( ! self::$instance) 
        { 
            self::$instance = new Loader(); 
        }         
        return self::$instance; 
    }

	private function _str_to_array($string = '')
	{
		if (is_array($string))
		{
			return $string;
		}

	    if (strpos($string, ','))
	    {
	       $string = explode(',', $string);
	    }
	    else
	    {
	       $string = array($string);
	    }
	
		return $string;
	}
	/**
     * Cargar librerias quee llegan desde un array, si existe no la carga de nuevo
     *
     * @param  array|string
     * @return true|false
     */
	function library($librerias = NULL, $params = array())
	{
		if (is_null($librerias))
		{
			return;		
		}

		$librerias = $this->_str_to_array($librerias);

		$router = Router::getInstance();

		foreach ($librerias as $libreria)
		{	    
		    $objeto = $libreria;
		    		    
		    if ( is_array($libreria))
		    {
		        $objeto    = array_values($libreria);		        
                $objeto    = $objeto[0];
                
                $libreria  = array_keys($libreria); 
                $libreria  = $libreria[0];     
		    }
		    
		    $clase = $libreria;
    
		    $directories = array(
		    	SYSTEM_PATH.'libraries/'.$libreria.EXT => TRUE,
		    	APP_PATH.'libraries/'.$libreria.EXT    => TRUE,
		    	APP_PATH.$router->package.'/'.$router->controller.'/libraries/'.$libreria.EXT => TRUE,
		    	APP_PATH.'core/'.$libreria.EXT => FALSE		    	
			);
			
		    if (strpos($libreria, '/') !== FALSE)
            {
                list($directorio, $clase) = explode('/', $libreria);
                $directories[APP_PATH.$directorio.'/'.$directorio.'/libraries/'.$clase.EXT] = TRUE;
            }
            
		    if (strpos($objeto, '/') !== FALSE)
            {
                list($directorio, $objeto) = explode('/', $libreria);
            }
			
			$file = NULL;
			$create_object = FALSE;

			foreach ($directories as $dir => $obj)
			{
				if ( ! file_exists($dir))
				{
					continue;
				}

    			include_once($dir);	
    			    				
    			if ( ! class_exists($clase) OR ! $obj)
    			{	
    				continue;
    			}
			
				$clase = ucfirst($clase);

				$this->objects[$objeto] = new $clase($params);   
			    		    
			    $controller = Controller::getInstance();
			    $controller->$objeto = $this->objects[$objeto];    			
    			
    			break;
			}					
		}


		return $this;
		
	}
	

	function set_vars($var = array(), $value = NULL)
	{
		if (is_string($var))
		{
			$var = array($var => $value);
		}

		foreach ($var as $key => $val)
		{
			$this->$key = $val;
		}

		return $this;
	}

	function view($file = null, $data = array(), $return = false, $manual = '')
	{
		if (is_null($file))
		{
			throw new Loader_Exception('There must be a view file to load, empty values can\'t be used');
		}

		$router = Router::getInstance();

		$view = $manual.$file;

		if ( ! file_exists($view) OR empty($manual))
		{
			$view = APP_PATH.'packages/'.$router->package.'/'.$router->subpackage.'/views/'.$file.EXT;

			if ( ! file_exists($view))
			{
				throw new Loader_Exception('The specified view could not be found<br>'.$view);
			}
		}

		foreach (Controller::getInstance() as $key => $val)
		{
			$this->$key = $val;
		}		
		
		$data AND extract($data);
						
		if ($return === true)
		{
		    ob_start();
		    include($view);
            $contents = ob_get_contents();             
            ob_end_clean();
            return  $contents;
		}

	    ob_start();
        include($view);
        ob_end_flush();
	}
	
	
		/**
	 * Recibe el nombre del archivo de un modelo, se le agregan datos necesarios
	 * para crear la ruta absoluta.Luego de esto instancia el objeto del modelo, 
	 * si el archivo existe lo incluye sino envia un 404 y termina la ejecucion
	 * 
	 * @param $model
	 * @return void
	 */
	function model($model = '', $obj = '', $create_object = true)
	{
		$file = APP_PATH.'models/'.$model.EXT;

		if (file_exists($file))
		{
			include_once($file);
		}
		else
		{
			$router = Router::getInstance();

			$file = APP_PATH.'packages/'.$router->package.'/'.$router->subpackage.'/models/'.$model.EXT;

			$sub = substr_count($model, '/');

			switch ($sub)
			{
				case 1:
					list($subpackage, $model) = explode('/', $model);    
                	$file = APP_PATH.'packages/'.$router->package.'/'.$subpackage.'/models/'.$model.EXT;            
					break;
				case 2:
					list($package, $subpackage, $model) = explode('/', $model);    
                	$file = APP_PATH.'packages/'.$router->package.'/'.$router->subpackage.'/models/'.$model.EXT;        
					break;
			}

            if (file_exists($file))
			{
			    require_once $file;
			}
			else
			{				
                throw new Loader_Exception('The model specified could not be found.<br>'.$file);
			}
		}
		
        if (strpos($model, '/') !== FALSE)
		{
		    $model = explode('/', $model);
		    $model = end($model);		    
		}

		if ($create_object === true)
		{
			$obj  		= ($obj == '') ? $model : $obj ;
			$base  		= Controller::getInstance(); 
			$model 		= ucfirst($model);			
			$base->$obj = new $model;
		}

		return $this;
	}
	
	function helper($helpers = '')
	{
	    $helpers = $this->_str_to_array($helpers);	 
	    $router  = Router::getInstance();

		foreach($helpers as $helper)
		{
		    
			$directories = array(
				SYSTEM_PATH.'helpers/'.$helper.EXT,
				APP_PATH.'helpers/'.$helper.EXT,
				APP_PATH.$router->package.'/helpers/'.$helper.EXT
			);
			
		    if (strpos($helper, '/') !== FALSE)
            {
                list($directorio, $file) = explode('/', $helper);
                $directories[] = APP_PATH.$router->package.'/helpers/'.$file.EXT;
            }

			foreach ($directories as $file)
			{
				if (file_exists($file))
				{
					include_once($file);
					break;
				}
			}
		}
		return $this;				
	}
	
	function presenter($presenter = '')
	{
	    $directories = array(
			RUTA_PRESENTERS.$presenter.EXT,
			RUTA_MODULOS.Router::getInstance()->carpeta.'/presenters/'.$presenter.EXT
		);
		
	    if (strpos($presenter, '/') !== FALSE)
        {
            list($directorio, $file) = explode('/', $presenter);
            $directories[] = RUTA_MODULOS.$directorio.'/presenters/'.$file.EXT;
        }

		foreach ($directories as $file)
		{
			if (file_exists($file))
			{
				include_once($file);
				break;
			}
		}
		
		return $this;
	}
	
	
			
}	

/* End of file loader.phtml */
/* Location: /system/loader.phtml */