<?php

function _post($item = NULL, $default = FALSE)
{
	if (input::getInstance()->post($item) === FALSE)
	{	    
		return $default;
	}
	return db::getInstance()->escape_str(input::getInstance()->post($item, TRUE), TRUE);
}

function _get($item = NULL, $default = FALSE)
{
	if (is_null($item) OR input::getInstance()->get($item) === FALSE)
	{
		return $default;
	}
	return db::getInstance()->escape_str(input::getInstance()->get($item, TRUE), TRUE);
}

function _set_token()
{
	$token = config::getInstance()->get('token');
	
	session::getInstance()->set($token, $token);
	
	return $token;	
}

function _check_token()
{
	$session = session::getInstance();
	$token   = input::getInstance()->post('token');
	$check   = $session->get($token) !== FALSE ? TRUE : FALSE ;
	$session->unset_var($token);
	if ($check === FALSE)
	{
		redirect(get_url());
	}
	return TRUE;
}