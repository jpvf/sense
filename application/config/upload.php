<?php 

return array(

	/*
	|--------------------------------------------------------------------------
	| Path to move the files to
	|--------------------------------------------------------------------------
	|
	| Just the folder where the files are going by default, you can override 
	| this configuration value when going initializing the class.
	|
	*/
	
	'upload_path' => 'uploads',

	/*
	|--------------------------------------------------------------------------
	| Allowed extensions
	|--------------------------------------------------------------------------
	|
	| Whatever extensions you are allowing to be uploaded to the server, has to
	| be separated by a pipe.
	|
	*/

	'allowed' => 'txt|doc|xls|csv|ppt|pdf|gif|jpg|jpeg|png|psd|xlsx|docx|pptx',

	/*
	|--------------------------------------------------------------------------
	| Max file size allowed
	|--------------------------------------------------------------------------
	|
	| Maximum file size allowed to be uploaded to the server in mb
	|
	*/

	'max_size' => '5',

);