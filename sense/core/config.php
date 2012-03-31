<?php 

namespace Sense\Core;
/**
 * Clase para manipular los archivos y datos de configuración
 *  
 * @author Juan Pablo Velandia Fraija
 * @version 2.0
 */
class Config 
{
    static $folder = NULL;
    private static $config;
    private static $files = array();

    /**
     * Carga los archivos de configuración
     * 
     * @param array $files contiene el o los archivos que se van a cargar
     */

    public static function load($files = array())
    {   
        if (is_string($files))
        {
            if (strpos($files, ','))
            {               
                $files = explode(',' , $files);
            }
            else
            {
                $files = array($files);
            }
        }

        foreach ($files as $file)
        {
            $file = trim($file);
        
            $config_file = APP_PATH.'config/'.$file.EXT;

            if (file_exists($config_file))
            {
                self::$files[$file]  = include($config_file);

                foreach (self::$files[$file] as $key => $val)
                {
                    self::$config[$key] = $val;
                }
            }

            if ( ! is_null(self::$folder))
            {
               $config_file = self::$folder.'config/'.$file.EXT;

                if (file_exists($config_file))
                {
                    self::$files[$file]  = include($config_file);

                    foreach (self::$files[$file] as $key => $val)
                    {
                        self::$config[$key] = $val;
                    }
                }
            }

        }
    }

    /**
     * Devuelve el valor de la llave de configuración solicitada, ej. Config::get('debug') devuelve TRUE/FALSE
     * 
     * @param string $key Indice de la configuración
     * @return mixed valor del indice solicitado
     */
    public static function get($key = '')
    {
        return isset(self::$config[$key]) ? self::$config[$key] : FALSE;
    }
    
    /**
     * Crea o actualiza valores de configuracion
     * 
     * @param array|string $file valores de configuracion, si es un string solamente sera el indice
     * @param array|string $value si $file es un string este será el valor del item de configuracion de lo contrario se pasa por alto
     */

    public static function set($file = array(), $value = '')
    {
        if (is_string($file))
        {
            $file = array($file => $value);
        }

        foreach ($file as $key => $val)
        {
            self::$config[$key] = $val;
        }
    }

    /**
     * Devuelve un array con el grupo de configuracion solicitado. Config::get('db') devolverá un array con los valores de configuracion del archivo db
     * 
     * @param string $group el array de configuración solicitado
     */
    public static function get_group($group = '')
    {
        return self::$files[$group];
    }

    /**
     * Crea o actualiza un grupo o array de configuración
     * 
     * @param array $group array con los valores de configuración
     * @param array $name nombre del grupo
     */
    public static function set_group($group = array(), $name = '')
    {
        if ($name == '' OR empty($group))
        {
            return FALSE;
        }

        self::$files[$name] = $group;

        self::set($group);
    }

}