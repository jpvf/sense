<?php

namespace Application\Core;

class App_Loader extends \Sense\Core\Loader {
	
	function helper($helpers = '')
	{
		echo $helpers;
		//return parent::helper($helpers);
	}


}