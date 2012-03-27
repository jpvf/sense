<?php 

namespace Sense\Libraries;

class Session{

    private static $instance;

    static function getInstance() 
    { 
        if ( ! static::$instance) 
        { 
            static::$instance = new Session(); 
        } 
        return static::$instance; 
    }
    
    function __construct()
    {
        static::$instance = $this;
        
        $this->input = \Sense\Core\Input::getInstance();
        
        isset($_SESSION) OR session_start();
        
        if (isset($_SESSION['flashdata']['old']))
        {
            unset($_SESSION['flashdata']['old']);
        }
        
        if (isset($_SESSION['flashdata']['old']))
        {
            $_SESSION['flashdata']['old'] = $_SESSION['flashdata']['new'];
            unset($_SESSION['flashdata']['new']);
        }
        
        $this->clean_cache();
    }
    	
    private function _set_vars($vars = array(), $val = '', $flashdata = false)
    {
        if ( is_string($vars) AND ! empty($val))
        {
            $vars = array($vars => $val);
        }

        foreach($vars as $key => $val)
        {
            if ($flashdata !== false)
            {
                $_SESSION['flashdata']['new'][$key] = $val; 
            }
            else
            {
                $_SESSION[$key] = $val;
            }
        }
    }

   
    function set_flashdata($flash = array(), $val = '')
    {
        $this->_set_vars($flash, $val, TRUE);
        return $this;
    }

    function set($key = null, $value = '')
    {
        if ( ! is_null($name))
        {
            $this->_set_vars($name, $value);                        
        }
        return $this;
    }
    

    function get_flashdata($key)
    {
        if ( ! isset($_SESSION['flashdata']['old'][$key]))
        {
            return FALSE;
        }
        
        return $_SESSION['flashdata']['old'][$key];
    }
    
	function get($key = NULL, $key2 = NULL)
	{
        if (is_null($key))
        {
            return FALSE;
        }

        if ( ! is_null($key2) AND isset($_SESSION[$key][$key2]))
        {
           return $_SESSION[$key][$key2]; 
        }
        elseif (isset($_SESSION[$key]))
        {
           return $_SESSION[$key]; 
        }

        return FALSE;     
	}
	
	function get_session()
	{
	   return $_SESSION;
	}
 
	function unset_var($key = NULL, $key2 = NULL)
    {
        if (is_null($key))
        {
            return FALSE;
        }

        if ( ! is_null($key2) AND ! is_null($key))
        {
           unset($_SESSION[$key][$key2]); 
        }

        if (isset($_SESSION[$key]))
        {
           unset($_SESSION[$key]); 
        }

        return $this;     
    }
    

    //----------------------------/
	function register($userdata = array(), $site = 'default')
    {
        $_SESSION[$site] = array('userdata' => $userdata);
    }

    function end_current($redirect = TRUE)
    {
    	$_SESSION = array();
    	$_COOKIE  = array();
        @session_unset();
        @session_destroy();
        $this->clean_cache();
        
        if ($redirect === TRUE)
        {
        	redirect();
        }
    }


    function clean_cache()
    {
        session_cache_limiter('nocache');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
        header("Cache-Control: private, no-store, no-cache, must-revalidate"); 
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

   
    public function token($nombre = 'token', $token)
    {
    	$this->set($nombre, $token);
    	return $token;
    }

    public function get_session_id()
    {
        return session_id();
    }

}