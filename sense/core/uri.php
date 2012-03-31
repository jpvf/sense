<?php 

namespace Sense\Core;
/**
 * Clase URI para el manejo de los parametros que entran por la URI.
 *
 */
class Uri{

    private static $instance;
    private $uri_string;
    private $segments;
    private $fetch_uri;
    var $rsegments = array();

    /**
     * Retorna el objeto que se instancia la primera vez, asi se evita crear basura con los objetos
     * @return uri
     */
    public static function getInstance()
    {
        if ( ! self::$instance)
        {
            self::$instance = new Uri;
        }
        return self::$instance;
    }
    
    function __construct()
    {
        show(__CLASS__ . ' iniciada <br>');
        
        $this->input    = Input::getInstance();
        
        // Is the request coming from the command line?
		if (php_sapi_name() == 'cli' or defined('STDIN'))
		{
			$this->init($this->_parse_cli_args());
			return $this;
		}
		
        $this->init($this->input->server('REQUEST_URI',TRUE));

        return $this;
    }
    
    private function _parse_cli_args()
	{
		$args = array_slice($_SERVER['argv'], 1);

		return $args ? '/' . implode('/', $args) : '';
	}

    function init($uri = '')
    {        
        $security = Security::getInstance();
        
        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
        {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }
        elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
        {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }
        
        $uri = substr($uri, 1);

        $search = array(
            '#(/|)\?.*#',
            '#/{2,}#',
        );

        $uri      = preg_replace($search, '', $uri);
        $segments = explode('/', $uri); 

        $this->segments = $segments;

        foreach ($segments as $key => $val)
        {
            $this->rsegments[] = $security->xss_clean($val);
        }
        
        return $this;
    }
    
    function get($param = NULL)
    {       
        if (is_null($param))
        {
            return $this->get_uri_string();
        }
        return (isset($this->segments[$param - 1])) ? $this->segments[$param - 1] : '';
    }
    
    
    function fetch_uri()
    {
        $this->fetch_uri = $this->segments;        
        return $this->fetch_uri;
    }
    
    function get_uri_string()
    {
        $this->uri_string = implode('/',$this->segments);
        return $this->uri_string;
    }

}