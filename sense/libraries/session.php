<?php 

namespace Sense\Libraries;

class Session{

    private static $session;
    
    function __construct()
    {
        self::$session = $this;
        
        $this->input = \Sense\Core\Input::getInstance();
        
        if ( ! isset($_SESSION))
        { 
        	session_start();
        }	
        
        if (isset($_SESSION['flashdata']['vieja']))
        {
            unset($_SESSION['flashdata']['vieja']);
        }
        
        if (isset($_SESSION['flashdata']['nueva']))
        {
            $_SESSION['flashdata']['vieja'] = $_SESSION['flashdata']['nueva'];
            unset($_SESSION['flashdata']['nueva']);
        }
        
        $this->clean_cache();
    }
    	
	static function getInstance() 
    { 
        if ( ! self::$session) 
        { 
            self::$session = new session(); 
        } 
        return self::$session; 
    }

    private function _set_vars($vars = array(), $val = '', $flashdata = FALSE)
    {
         if ( is_string($vars) && ! empty($val))
        {
            $vars = array($vars => $val);
        }
        
        if(count($vars) > 0)
        {
            foreach($vars as $key => $val)
            {
                if ($flashdata !== FALSE)
                {
                    $_SESSION['flashdata']['nueva'][$key] = $val; 
                }
                else
                {
                    $_SESSION[$key] = $val;
                }
            }
        }
    }

   
    function set_flashdata($flash = array(), $val = '')
    {
        $this->_set_vars($flash, $val, TRUE);

        return $this;
    }

    function set($name = array(), $value = '')
    {
        $this->_set_vars($name, $value);
        return $this;
    }
    
    //MODIFICAR NOMBRE
    function flashdata($key)
    {
        if ( ! isset($_SESSION['flashdata']['vieja']))
        {
            return FALSE;
        }
        
        if ($_SESSION['flashdata']['vieja'][$key] )
        {
        	return $_SESSION['flashdata']['vieja'][$key];
        }
        return FALSE;
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