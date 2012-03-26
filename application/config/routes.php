<?php 
/* 
 * Configuracion de las rutas del sistema
 * 
 * Es posible configurar rutas de cualquier forma, ejemplo: 
 * 
 * Ruta real
 *  
 *  /controlador/metodo/variables/
 *  
 *   o si esta dentro de una carpeta
 * 
 *  /carpeta/controlador/metodo/variables/
 *  
 * 
 * $route['ruta_del_navegador'] = 'ruta_real';
 * 
 * (:numero) significa que puede va a llegar un numero en el segmento de la uri
 * (:letra) significa que llegara una cadena alfanumerica
 * (:cualquiera) significa que llegara o un numero o una cadena o una combinacion
 * 
 * Con el signo $ se identifica donde se va a ubicar el reemplazo que se haga y el numero que lo
 * acompaña indica, de izquierda a derecha, el indice del reemplazo, si se tienen (:numero)/(:letra)
 * donde se reemplaza (:numero) sera $1 y donde se reemplaza (:letra) será $2 por ejemplo: 
 * 
 * uri entrante = 'controlador/2/3'
 * 
 * $route['controlador/(:numero)/(:numero)'] = 'controlador/index/$1/$2'
 * 
 * $1 = 2 
 * $2 = 3
 * 
 * daria como resultado
 * 
 * controlador/index/2/3 
 * 
 */

return array(

	'default' => 'account/payments',

	'admin'   => 'account',

);