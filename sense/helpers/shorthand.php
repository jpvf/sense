<?php

if ( ! function_exists('_uid'))
{
	function _uid($table = null)
	{
	    $number = _random_id();
	    
	    $results = db::getInstance()->where("uid = $number")->get($table);

	    if ($results->num_rows() > 0)
	    {
	        return _uid($table);
	    }

	    return $number;
	}
}

if ( ! function_exists('_random_id'))
{
	function _random_id()
	{
	    $number = '';

	    for ($i=0; $i<8; $i++) 
	    { 
	        $number .= rand(1,9);         
	    }

	    return $number;
	}
}

if ( ! function_exists('_post'))
{
	function _post($item = null, $default = false)
	{
		$input = Input::getInstance()->post($item, true);

		return $input ?: $default;
	}
}

if ( ! function_exists('_get'))
{
	function _get($item = null, $default = false)
	{
		$input = Input::getInstance()->get($item, true);

		return $input ?: $default;
	}
}