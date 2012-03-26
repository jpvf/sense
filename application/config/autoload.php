<?php 
/*
 * Archivo de configuracion para auto cargar librerias.
 * 
 * Se pueden autocargar: Librerias, helpers y archivos de lenguaje
 * El formato debe ser : $autoload['tipo'] = array('libreria', 'libreria1');
 * 
 * Solo debe existir un array por cada item.
 */
 
return array(

	'libraries' => array(
		'modules', 
		'breadcrumbs', 
		'template', 
		'assets', 
		'perms', 
		'form',
	    'auth',
		'acl'
	),

	'helpers' => array(
		'helpers', 
		'debug', 
		'html', 
		'url', 
		'date_helper', 
		'page', 
		'form',
		'error', 
		'users', 
		'tabs', 
		'input', 
		'users',
		'security', 
		'language',
		'messages',
		'validation',
	    'settings/settings_helper'
	),

	'override' => array(
		''
	),

	'language' => array(),

);

/* Fin del archivo autoload.phtml */
/* Ubicacion: /config/autoload.phtml */