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
 * Muestra las etiquetas <br>, recibe un parametro entero para el número de repeticiones
 * del <br>
 *
 * @access  public
 * @param   integer
 * @return  string
 */

if ( ! function_exists('br'))
{   
    function br($repeat = 1)
    {
        $br = '';
        for ($i = 0;$i < $repeat; $i++)
        {
           $br .= '<br />';
        }
        return $br;
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