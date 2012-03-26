<?php 

class Perms{

  	var $perms = array();        //Array : Stores the permissions for the user  
    var $userID = 0;            //Integer : Stores the ID of the current user  
    var $userRoles = array();  //Array : Stores the roles of the current user  
    private static $acl;
    
    public static function getInstance()
    {
    	if (!self::$perms) 
        { 
            self::$perms = new Perms(); 
        } 
        return self::$perms; 
    }

	function getUserRoles($action)  
	{  
	    $sql = "SELECT access_actions.id_action,access_actions.action_name,access_actions.id_module,access_actions.keyaction
	    		FROM access_perms
	    		JOIN access_actions ON(access_actions.id_action = access_perms.id_action)
	    		JOIN users ON(access_perms.id_profile = users.id_profile)
	    		WHERE users.id_user = {$_SESSION['userdata']['id']} AND access_actions.keyaction = '$action'
	    		 AND access_perms.active = 1";
	    $sql = mysql_query($sql);
	    return mysql_numrows($sql);
	}  
	
	function getProfileRoles($action,$profile)  
	{  
	    $sql = "SELECT access_actions.id_action,access_actions.action_name,access_actions.id_module,access_actions.keyaction
	    		FROM access_perms
	    		JOIN access_actions ON(access_actions.id_action = access_perms.id_action)
	    		JOIN users ON(access_perms.id_profile = users.id_profile)
	    		WHERE access_perms.id_profile = $profile AND access_actions.keyaction = '$action' AND access_perms.active=1";
	    $sql = mysql_query($sql);
	    return mysql_numrows($sql);
	}  
	
	public function has_perm($action = '', $profile = '')  
	{  
		$has_permission = false;
	    if($action != ''){
	    	if($profile == ''){
	    		$perms = $this->getUserRoles($action);	
	    		if($perms > 0){
	    			$has_permission = true;
	    		}	    	
	    	}else{
	    		$perms = $this->getProfileRoles($action,$profile);		
	    		if($perms > 0){
	    			$has_permission = true;
	    		}	
	    	}
	    }
	    return $has_permission;
	}  
	

}