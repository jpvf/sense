<?php

class Settings_Model extends \Application\Core\App_Model {
	
	function find()
	{
		return $this->db->get('settings_items');
	}

}