<?php 

namespace Sense\Core;

class Uri{

    private static $instance;

    private $uri_string;
    private $segments;
    private $fetch_uri;
    public  $rsegments = array();

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

        $uri = $_SERVER['REQUEST_URI'];

        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
        {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        }
        elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
        {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        $uri = preg_replace('#\?.*#', '', trim($uri, '/'));

        $this->uri_string = $uri;
        $this->segments   = $this->rsegments = explode('/', $uri);         
    }

    function get($param = NULL)
    {       
        if (is_null($param))
        {
            return $this->get_uri_string();
        }

        return (isset($this->segments[$param - 1]) ? $this->segments[$param - 1] : '');
    }
    
    
    function fetch_uri()
    {        
        return $this->segments;
    }
    
    function fetch_uri_string()
    {
        return $this->uri_string;
    }

}