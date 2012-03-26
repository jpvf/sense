<?php

/**
 * Libreria de autenticación de usuarios.
 * 
 * @package JFramework
 * @author Juan Velandia
 * @version 2011
 * @access public
 */
class Auth {

    
	private $errors = array();
    private $_config;
    
	/**
	 * Carga la configuración del sistema.
	 * 
	 * @return void
	 */
	function __construct()
	{
        $this->_config = new stdClass();
		Config::load('auth');
        $config = Config::get_group('auth');
        
        foreach ($config as $key => $val)
        {
            $this->_config->$key = $val;
        }        
	}

	/**
	 * Realiza el login del usuario
	 * 
	 * @param string $username
	 * @param string $password
	 * @return
	 */
	function login($username = '', $password = '', $site = 'default')
	{
		if (empty($username) OR empty($password))
		{
			return FALSE;
		}

		$db = db::getInstance();
		$session = Session::getInstance();

		$password = salt_it($password);
        
		$results = $db->where("{$this->_config->username_field} = '$username' AND password = '$password'")
					  ->get('users');

		if ($results->num_rows() === 0)
		{
			return FALSE;
		}				

		$user = $results->row();
		
		$session_id = $db->update(array(
			'session_id' => $session->get_session_id()
		), 'users', "id = {$user->id}");

		$user->ip_address = Input::getInstance()->ip_address();
		
		$session->register((array) $user, $site);
		
		Acl::getInstance()->set_data((array) $user);
		return TRUE;		
	}

	/**
	 * Auth::is_logged_in()
	 * 
	 * @return
	 */
	function is_logged_in($site = 'default')
	{
		$session = Session::getInstance();

		$session->clean_cache();

		$userdata = $session->get($site, 'userdata');

		if ( ! empty($userdata))
    	{
    		$data = db::getInstance()->select('users.session_id')
    								 ->from('users')
    								 ->where('users.id = '.$userdata['id'])
    								 ->query();
  			
  			if ($data->num_rows() === 0)
  			{
  				return FALSE;
  			}

  			$data = $data->row();
    		
	    	if ($data->session_id !== session_id())
	    	{
	    	    //$session->end_current();
    		}

	    	if ($userdata['ip_address'] !== Input::getInstance()->ip_address())
	    	{
	    	 	//$session->end_current();
	    	}	    	

	    	return TRUE;
    	}
    	return FALSE;
	}

	/**
	 * Auth::logout()
	 * 
	 * @return
	 */
	function logout()
	{
		Session::getInstance()->end_current();
        
        //redirect(get_url()."/".$this->_config->logout);
	}

	
}