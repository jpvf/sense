<?php

class Account_Model extends \Sense\Core\Model {
	
	function find()
	{
		return $this->db->get('users');	
	}
	

}