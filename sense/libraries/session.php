<?php namespace Sense\Libraries;

class Session {
    
    /**
     * Init
     *
     * Start the session, clean up the flash vars and empty the cache
     * to avoid any issues with authentication.
     *
     */

    static function init()
    {        
        isset($_SESSION) OR session_start();

        session_regenerate_id();

        if (isset($_SESSION['flashdata']['old']))
        {
            unset($_SESSION['flashdata']['old']);
        }
        
        if ( ! isset($_SESSION['flashdata']['old']) AND isset($_SESSION['flashdata']['new']))
        {
            $_SESSION['flashdata']['old'] = $_SESSION['flashdata']['new'];
            unset($_SESSION['flashdata']['new']);
        }
        
        static::clean_cache();
    }
    	
    private static function _set_vars($vars = array(), $val = '', $flashdata = false)
    {
        if ( ! is_array($vars) AND ! empty($val))
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

    public static function has($item = '')
    {
        return isset($_SESSION[$item]) AND ! empty($item) ? true : false;
    }

   
    public static function flash($flash = array(), $val = '')
    {
        static::_set_vars($flash, $val, TRUE);
    }

    public static function set($key = null, $value = '')
    {
        if ( ! is_null($key))
        {
            static::_set_vars($key, $value);                        
        }
    }    

    public static function get_flash($key)
    {
        if (isset($_SESSION['flashdata']['old'][$key]))
        {
            return $_SESSION['flashdata']['old'][$key];
        }
        
        return false;
    }
    
	public static function get($key = null, $default = null)
	{
        if (isset($_SESSION[$key]))
        {
           return $_SESSION[$key]; 
        }

        return $default;     
	}
	
	public static function all()
	{
	   return $_SESSION;
	}
 
	public static function remove($key = null)
    {
        if (isset($_SESSION[$key]) AND ! is_null($key))
        {
           unset($_SESSION[$key]); 
           return true;
        }

        return false;     
    }   

    public static function destroy()
    {
    	$_SESSION = array();
    	$_COOKIE  = array();
        @session_unset();
        @session_destroy();
        @session_regenerate_id();
        static::clean_cache();
    }

    public static function clean_cache()
    {
        session_cache_limiter('nocache');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
        header("Cache-Control: private, no-store, no-cache, must-revalidate"); 
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

    public static function id()
    {
        return session_id();
    }

}