<?php

namespace Sense\Core;

class Language {

	private static $language = array();
	private $is_loaded	= array();
	private $lang;
	private static $instance;

	function __construct()
	{
		//show(__CLASS__ . ' iniciada <br>');		
	}
	
    public static function getInstance() 
    { 
        if ( ! self::$instance) 
        { 
            self::$instance = new Language(); 
        } 
        return self::$instance; 
    }

	function load($idiom = '')
	{
		$langfile = "lang_$idiom" . EXT; 
		
		if ($langfile  == $this->is_loaded)
		{
			return;
		}

		if ($idiom == '')
		{
			$deft_lang = Config::get('default_lang');
			$idiom 	   = ($deft_lang == '') ? 'es' : $deft_lang;
		}

		$this->lang = $idiom;
		
		$langfile = "lang_$idiom" . EXT;
		
		if (file_exists(APP_PATH.'languages/'.$langfile))
		{
			include(APP_PATH.'languages/'.$langfile);
		}
	

		if ( ! isset($lang))
		{
			return;
		}

		$this->is_loaded = $langfile;
		self::$language  = $lang;

		return TRUE;
	}

	static function line($line = '')
	{
		$line = ($line == '' OR ! isset(self::$language[$line])) ? FALSE : self::$language[$line];
		return $line;
	}

}