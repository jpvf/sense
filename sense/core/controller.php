<?php

namespace Sense\Core;
/**
 * Controlador base
 * 
 * @author JuanPablo
 * @version 1.3 
 */
class Controller{

    private static $instance; 
    
    public static function getInstance()
    {
        if ( ! self::$instance)
        {
            self::$instance = new Controller();
        }
        return self::$instance;
    }
    
    /**
     * Inicia objetos y librerias para 
     * la clase hija.
     * 
     * @return void
     */
    function __construct()
    {      
        self::$instance = $this;

        $this->load     = Loader::getInstance();
        $this->uri      = Uri::getInstance();
        $this->input    = Input::getInstance();         
        $this->language = Language::getInstance();
        $this->db       = \Sense\Database\db::getInstance();
        $this->forge    = \Sense\Database\Forge::getInstance();
        
        foreach ($this->load->objects as $key => $val)
        {
            if ( ! isset($this->$key))
            {
                $this->$key = $val;
            }
        }

        if (isset($this->language))
        {
            $this->language->load(\Session::get('user_lang'));
        }

	}

	/**
	 * Función para ejecutar acciones antes que se inicialice el controlador de la aplicación
	 */
    function before(){}

    /**
	 * Función para ejecutar acciones después que se inicialice el controlador de la aplicación
	 */
    function after(){}
  
}