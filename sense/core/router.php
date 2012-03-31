<?php 

namespace Sense\Core;

class Router {

    public $directorio  = '';
    public $controlador = '';
    public $carpeta     = '';
	private static $instance;
	
	public static function getInstance()
	{
		if ( ! self::$instance)
		{
			self::$instance = new Router();
		}
		return self::$instance;
	}
	
	function __construct()
    {
        show(__CLASS__ . ' iniciada <br>');
        $this->uri = Uri::getInstance();
    }
	
	function set()
	{ 		    
        Config::load('routes');

        $uri_string = $this->uri->fetch_uri_string();

        //Es necesario traer al controlador del config?
		$uri = ( ! trim($uri_string, '/') ? $this->_default_controller() : $this->uri->fetch_uri());

        //Revisa las rutas predefinidas para sobreescribir la actual
        $segments = $this->_custom_routes($uri);    

		return $this->_get_route();
	}
	
	private function _default_controller()
	{ 
	    if (strpos(Config::get('default'), '/') !== false)
        {
            return explode('/', Config::get('default'));
        }
        return array(Config::get('default'));
	}
	
	private function _custom_routes($segments = array())
	{
        $routes = Config::get_group('routes');

        unset($routes['default']);

	    $uri = implode('/', $segments);

        if (isset($routes->$uri))
        {
            return $this->_validate(explode('/', $routes->$uri));
        }

        foreach ($routes as $key => $val)
        {
            $key = str_replace(':any', '.+', str_replace(':letter', '[a-z_-]+', str_replace(':num', '[0-9]+', $key)));

            if ( ! preg_match('#^'.$key.'$#', $uri))
            {
                continue;
            }

            if (strpos($val, '$') !== false AND strpos($key, '(') !== false)
            {
                $val = preg_replace('#^'.$key.'$#', $val, $uri);        
                            
            }
            return $this->_validate(explode('/', $val));
            
        }
        return $this->_validate($segments);
	}
		
	private function _validate($segments = array())
	{
        if (is_dir(APP_PATH.'packages/'.$segments[0]))
        {
            $segments[1] = (isset($segments[1])) ? $segments[1] : '';

            //Es una subcarpeta con nombre distinto al de la carpeta?
            if (is_dir(APP_PATH.'packages/'.$segments[0].'/'.$segments[1]))
            {
                $base = APP_PATH.'packages/'.$segments[0].'/'.$segments[1].'/';

                $segments[2] = (isset($segments[2])) ? $segments[2] : '';

                //Es un archivo pero con distinto nombre a la carpeta del modulo?
                if (file_exists($base.'controllers/'.$segments[2].EXT))
                {
                    $this->package    = $segments[0];
                    $this->controller = $segments[2];
                    $this->subpackage = $segments[1];
                    $this->slice      = 4;
                    $this->method     = (isset($segments[3]) ? $segments[3] : 'index');
                    $this->file       =  $base.'controllers/'.$segments[2].EXT;
                    return $segments;
                }

                //es un archivo dentro de la subcarpeta?
                if (file_exists($base.'controllers/'.$segments[1].EXT))
                {
                    $this->package    = $segments[0];
                    $this->controller = $segments[1];
                    $this->subpackage = $segments[0];
                    $this->slice      = 3;
                    $this->method     = $segments[2] ?: 'index';
                    $this->file       =  $base.'controllers/'.$segments[1].EXT;
                    return $segments;
                }
            }

            if (is_dir(APP_PATH.'packages/'.$segments[0].'/'.$segments[0]))
            {
                $base = APP_PATH.'packages/'.$segments[0].'/'.$segments[0].'/';

                $segments[1] = (isset($segments[1])) ? $segments[1] : '';

                //Es un archivo pero con distinto nombre a la carpeta del modulo?
                if (file_exists($base.'controllers/'.$segments[1].EXT))
                {
                    $this->package    = $segments[0];
                    $this->controller = $segments[1];
                    $this->subpackage = $segments[0];
                    $this->slice      = 3;
                    $this->method     = (isset($segments[2])) ? $segments[2] : 'index';
                    $this->file       =  $base.'controllers/'.$segments[1].EXT;
                    return $segments;
                }

                //es un archivo dentro de la subcarpeta?
                if (file_exists($base.'controllers/'.$segments[0].EXT))
                {
                    $this->package    = $segments[0];
                    $this->controller = $segments[0];
                    $this->subpackage = $segments[0];
                    $this->slice      = 3;
                    $this->method     = (isset($segments[2])) ? $segments[2] : 'index';
                    $this->file       =  $base.'controllers/'.$segments[0].EXT;
                    return $segments;
                }
            }
        }        
        //Si llega hasta aca es que no hay mas que hacer.
		Error::getInstance()->trigger_error(404);
	}
		
    private function _get_route()
    {        
        return (object) array(
        	'package'     => $this->package,  
            'slice'       => $this->slice,
            'controller'  => $this->controller, 
            'method'      => $this->method,
            'file'	      => $this->file
        );		
	}

}