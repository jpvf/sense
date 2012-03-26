<?php
/*
 * Archivo de configuracion general del sistema.* 
 */ 

return array(

	/*
	|--------------------------------------------------------------------------
	| Sets the actual url
	|--------------------------------------------------------------------------
	|
	| This variable will set the base url for the entire system for anchors, 
	| forms, redirects etc. It will only find the actual url as in 
	| http://www.example.com/.
	|
	*/

	'base_url' => ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http")."://".$_SERVER['HTTP_HOST'].'/',

	/*
	|--------------------------------------------------------------------------
	| Application Index
	|--------------------------------------------------------------------------
	|
	| If you are including the "index.php" in your URLs, you can ignore this.
	| However, if you are using mod_rewrite to get cleaner URLs, just set
	| this option to an empty string.
	|
	*/

	'index_page' => "index.php",
);

/*
 * Url del sistema debe contener, el slash del final y el protocolo http.
 */
$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['base_url'] .= "://".$_SERVER['HTTP_HOST']  . '/';

/*
 * Esta es la pagina de inicio del sistema por donde todo va a ser enrutado. Si se usa el modrewrite de apache para esconderlo, dejarlo en blanco
 *  asi: "".
 */
$config['index_page']         = "index.php";


/*
 * Email por defecto del sistema
 */
$config['admin_email'] 		  = "developers@totalcode.com";

/*
 * Llave para codificar y decodificar dentro del sistema
 */
$config['salt'] 	   		  = "B2579P3468SALT"; 

/*
 * Indica si se deben o no limpiar las variables globales por defecto, puede afectar el rendimiento.
 */
$config['sanitize_globals']   = FALSE; 

/*
 * Indica si se van a utilizar archivos de lenguaje dentro del sistema
 */
$config['lang_files']		  = TRUE; 

/*
 * Lenguaje por defecto, solo las 2 letras que lo identifican: es, en, fr, pt
 */
$config['default_lang']		  = 'es';//idioma por defecto

/*
 * Controlador por defecto, si solo aparece el index.phtml en la url se llegará aca.
 */
$config['default_controller'] = 'home'; 

/*
 * Token de validacion en los formularios o donde se necesite
 */
$config['token']			  = md5(sha1(uniqid(rand())));//token para validarlo en los formularios

/*
 * Tiempo de vida de la sesion del ususario
 */
$config['session_time']		  = 30;

/*
 * Se puede utilizar una extension de archivos al final de la url, no afectaran en nada y son unicamente
 * visuales.
 */
$config['url_extension']	  = '';

/* Fin del archivo config.phtml */
/* Ubicacion: /config/config.phtml */