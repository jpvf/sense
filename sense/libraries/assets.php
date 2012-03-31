<?php

class Assets
{

	private static $_css = array();
	private static $_js  = array();
	
	static function css($css = '', $path = 'assets/css', $ext = '.css')
	{		
	    $css = static::_string_to_array($css);        
		
		foreach($css as $row)
		{
			$src = base_url().$path.'/'.trim($row).$ext;			
			static::$_css[] = "<link type='text/css' href='$src' rel='stylesheet' />";			
		}
	}

	static function js($script = '', $path = 'assets/js', $ext = '.js')
	{		
		$script = self::_string_to_array($script);

		foreach($script as $row)
		{
			$src = base_url().$path.'/'.trim($row).$ext;
			self::$_js[] = "<script type='text/javascript' src='$src' ></script>";	
		}	
	}

	static private function _string_to_array($items = NULL)
	{
		if( ! is_array($items))
		{		    
		   if (strpos($items, ',') !== FALSE)
           {
               $items = explode(',', $items);    
           }
           else 
           {
		       $items = array($items);
           }
		}

		return $items;
	}
	
	static function show_js()
	{
		return ( ! empty(static::$_js)) ? implode("\n\t", static::$_js) : '';
	}
	
	static function show_css()
	{  
		return ( ! empty(static::$_css)) ? implode("\n\t", static::$_css) : '';
	}
}