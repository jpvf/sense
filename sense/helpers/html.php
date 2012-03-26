<?php 

/**
 * Genera espacios en html dependiendo del numero que se solicite
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('nbsp'))
{
    function nbsp($repeticion = 1)
    {
        $nbsp = '';
        for($i = 0; $i < $repeticion; $i++)
        {
            $nbsp .= '&nbsp;';
        }
        return $nbsp;
    }
}  
/**
 * Agrega la imagen cuando un llamado en ajax se esta ejecutando.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('ajax_image'))
{
    function ajax_image($white = FALSE)
    {
        if ($white === TRUE)
        {
            return '<span class="loading align-right hidden"><img src="' . base_url() . 'assets/images/ajax-loader.gif" /></span>';
        }
        return '<span class="loading align-right hidden"><img src="' . base_url() . 'assets/images/ajax-loader.gif" /></span>';
    }
}      

/**
 * Crea una lista UL
 *
 * @access  public
 * @param   array
 * @return  string
 */

if( ! function_exists('ul'))
{   
    function ul($items = array(), $attr = array(), $zebra = FALSE)
    {
        return create_list('ul', $items, $attr, $zebra);
    }
}

/**
 * Crea una lista UL
 *
 * @access  public
 * @param   array
 * @return  string
 */

if( ! function_exists('ol'))
{   
    function ol($items = array(), $attr = array(), $zebra = FALSE)
    {
        return create_list('ol', $items, $attr, $zebra);
    }
}

/**
 * Crea una lista
 *
 * @access  public
 * @param   array
 * @return  string
 */

if( ! function_exists('create_list'))
{   
    function create_list($type = 'ul', $items = array(), $attr = array(), $zebra = FALSE)
    {
        $lists = array('ul', 'ol');
        
        if (count($items) == 0 OR ! in_array($type, $lists))
        {
            return FALSE;
        }
        
        $attributes = '';
        
        foreach ($attr as $key => $val)
        {
            $attributes .= " $key='$val'";
        }
        
        $list = "<$type $attributes>";
        
        $c = 0;
        
        foreach ($items as $item)
        {
            $class = '';
            
            if ($zebra === TRUE)
            {
                $class = ($c++%2==1) ? '' : ' class="even"';
            }
            
            $list .= '<li' . $class . '>';
            
            if (is_array($item))
            {
                $list .= create_list($type, $item);
            }
            else
            {
                $list .= $item;
            }
            
            $list .= '</li>';
        }
        
        $list .= "</$type>";
        
        return $list;
    }
}

/**
 * Muestra un span con fuente de color rojo y doble espacio para mensajes del sistema.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('span'))
{
    function span($msg = '', $color = 'red')
    {
       echo br() . '<span style="color:' . $color .'">' . $msg .'</span>' . br(2);
    }
}      

/**
 * Verifica que el token que llega por post sea similar al que esta almacenado en la sesion, 
 * si no coinciden redirige y muestra un error
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('check_token'))
{    
    function check_token($token = 'token', $redirect = '', $return = FALSE)
    {
        $input   = input::getInstance();
        $session = session::getInstance();
        
        if ( ($session->get($token) != $input->post($token)) OR !$input->post($token) OR !$session->get($token))
        {
            $session->unset_var($token);
            
            if ($return === FALSE)
            {
                mensaje_error("Ha ocurrido un error guardando la informaci&oacute;n int&eacute;ntelo de nuevo." , $redirect);
            }
            elseif ($return === TRUE)
            {
                return FALSE;
            }
        }
        
        $session->unset_var($token);
        
        if ($return === TRUE)
        {
            return TRUE;
        }
    
    }    
}      

/**
 * Muestra un span con la clase clearFix
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('clear_fix'))
{    
    function clear_fix()
    {
        return '<span class="clearFix"></span>';
    }
}      

/**
 * Muestra un mensaje del sistema puede ser de tipo de error, alerta, exito o informacion.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('messages'))
{
    function messages($message = '', $type = '', $sticky = FALSE)
    {
       if ( ! $message)
       {
            return;
       }
       
       $close = "<span class='close' id='hide' title='Cerrar'></span>";
       $class = "";
       
       if ($sticky === TRUE)
       {
            $close = '';
            $class = 'sticky';
       }
       
       $message = "<div class='message $type $class' style='display: block; '><p>$message</p>$close</div>";
       return $message;        
    }
}      

/**
 * Crea un mensaje de exito, verde.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('mensaje_ok'))
{    
    function mensaje_ok($mensaje = 'Operación completada con exito' , $url = '')
    {
       mensaje($mensaje, $url, 'success');
    }
}      

/**
 * Crea un mensaje de error, rojo.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('mensaje_error'))
{   
    function mensaje_error($mensaje = 'Error durante la operación' , $url = '')
    {
       mensaje($mensaje, $url, 'error');
    }
}      

/**
 * Crea un mensaje de informacion, azul.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('mensaje_info'))
{    
    function mensaje_info($mensaje = 'Mensaje de información' , $url = '')
    {
       mensaje($mensaje, $url, 'info');
    }
}      

/**
 * Crea un mensaje de alerta, amarillo.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('mensaje_alerta'))
{    
    function mensaje_alerta($mensaje = 'Mensaje de alerta' , $url = '')
    {
       mensaje($mensaje, $url, 'warning');
    }
}      

/**
 * Crea el mensaje almacenandolo en una variable de sesion que esta disponible para 
 * la siguiente peticion.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('mensaje'))
{   
    function mensaje($mensaje = '', $url = '', $tipo = '')
    {
        if ($url[0] != '/')
        {
            $url = '/' . $url;
        }
        
        $session = session::getInstance();
        $array = array('mensaje' => $mensaje , 'tipo' => $tipo);
        $session->set_flashdata($array);
        redirect(get_url() . $url); 
    }
}


function get_select_options($result = array(), $index = '', $value = '', $first_empty = false)
{
    $options = array();

    if ($first_empty === true)
    {
        $options[0] = '';
    }

    foreach ($result as $item)
    {
        $options[$item->$index] = $item->$value;
    }

    return $options;
}