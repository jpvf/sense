<?php

// Enviroment
define('ENVIROMENT', 'development');

// Let's hold Windows' hand and set a include_path in case it forgot
set_include_path(dirname(__FILE__));

// Some hosts (was it GoDaddy? complained without this
@ini_set('cgi.fix_pathinfo', 0);

// System folder path
$system_path = 'sense';

// Application folder path
$application_path = 'application';


// This file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

// The name of THIS file
define('BASE_PATH', pathinfo(__FILE__, PATHINFO_BASENAME));

// The PHP file extension
define('EXT', '.php');

// Path to the system folder
define('SYSTEM_PATH', str_replace("\\", "/", $system_path).'/');

// Base folder(s)
define('BASE_URL', dirname($_SERVER["SCRIPT_NAME"]));

// The path to the "application" folder
define('APP_PATH', $application_path.'/');

require_once SYSTEM_PATH.'core/constants'.EXT;
require_once SYSTEM_PATH.'bootstrap'.EXT;

function show($class = '')
{
	//echo $class;
}