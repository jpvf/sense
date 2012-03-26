<?php 

/**
 * Crea la linea de atributos de cada etiqueta
 * @access public
 * @param array, string
 * @return string
 */

if ( ! function_exists('form_attributes'))
{
	function form_attributes($attrs = array())
	{
		if ( ! is_array($attrs) AND ! empty($attrs))
		{
			return $attrs;
		}

		$attr = array();

		foreach ($attrs as $key => $val) 
		{
			$attr[] = "$key='$val'";
		}
		return implode(' ', $attr);
	}	
}

/**
 * Genera los distintos input
 *
 * @access public
 * @param string
 * @return string
 *
 */
if ( ! function_exists('form_default_input'))
{
	function form_default_input($type = 'text', $attrs = array())
	{
		if ( ! array_key_exists('value', $attrs))
		{
				$attrs['value'] = '';
		}

		return $input = "<input type='$type' " . form_attributes($attrs) . " />\n";
	}
}

/**
 * Crea la etiqueta de apertura del formulario
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_open'))
{
	function form_open($url = '' , $attributes = array())
	{
		$action = ( strpos($url, '://') === FALSE) ? get_url() . '/' . $url : $url;

		$form = '<form action="' . $action . '"';

		if ( ! array_key_exists('method', $attributes))
		{
				$form .= ' method="post"';
		}

		$form .= form_attributes($attributes);
		$form .= " >\n";
		return $form;
	}
}

/**
 * Crea la etiqueta de cierre del formulario
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_close'))
{	
	function form_close()
	{
		return "</form>\n";
	}
}

/**
 * Crea la etiqueta de apertura del formulario con el atributo enctype = multipart/form-data
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_open_multipart'))
{	
	function form_open_multipart($url = '', $attributes = array())
	{
		$attributes['enctype'] ="multipart/form-data";
		return form_open($url, $attributes);
	}
}

/**
 * Crea un <input type="hidden">  solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_hidden'))
{	
	function form_hidden($attributes = array())
	{
		return form_default_input('hidden', $attributes);
	}
}

/**
 * Crea un <input type="text">  solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_input'))
{	
	function form_input($attributes = array())
	{
		return form_default_input('text', $attributes);
	}
}

/**
 * Crea un <input type="password">  solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_password'))
{	
	function form_password($attributes = array())
	{
		return form_default_input('password', $attributes);
	}
}

/**
 * Crea un <input type="file">  solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_upload'))
{	
	function form_upload($attributes = array())
	{
		return form_default_input('file', $attributes);
	}
}

/**
 * Crea un <textarea>  solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_textarea'))
{	
	function form_textarea($attributes = array())
	{
		$textarea = '<textarea ';
		$textarea .= form_attributes($attributes);
		$textarea .= " >";

		if (array_key_exists('html', $attributes))
		{
			$textarea .= $attributes['html'];
		}

		$textarea .= "</textarea>\n";
		return $textarea;
	}
}

/**
 * Crea un <select> con los atributos y <options> solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_select'))
{	
	function form_select($attributes = array(), $options = array(), $selected = '')
	{
		$select = '<select ';
		$select .= form_attributes($attributes);
		$select .= ">\n";

		foreach ($options as $value => $option)
		{
			$sel = '';

			if ($value == $selected)
			{
				$sel = 'selected="selected"';
			}

			$select .= '<option value="'. $value .'" ' . $sel . '>' . $option ."</option>\n";
		}

		$select .= "</select>\n";
		return $select;
	}
}

/**
 * Crea un <input type="checkbox"> con los atributos solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_checkbox'))
{	
	function form_checkbox($attributes = array())
	{
		if (key_exists('checked', $attributes))
		{
			if ($attributes['checked'] === TRUE)
			{
				$attributes['checked'] = 'checked';					
			}
			else
			{
				unset($attributes['checked']);
			}
		}

		return form_default_input('checkbox', $attributes);
	}
}

/**
 * Crea un <input type="radio"> con los atributos solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_radio'))
{	
	function form_radio($attributes = array())
	{
		if (key_exists('checked', $attributes))
		{
			if ($attributes['checked'] === TRUE)
			{
				$attributes['checked'] = 'checked';					
			}
			else
			{
				unset($attributes['checked']);
			}
		}

		return form_default_input('radio', $attributes);
	}
}

/**
 * Crea un <input type="submit"> con los atributos solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_submit'))
{	
	function form_submit($attributes = array())
	{
		return form_default_input('submit', $attributes);
	}
}

/**
 * Crea un <label> con los atributos solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_label'))
{	
	function form_label($for = '', $html = '', $attributes = array())
	{
		$label = '<label for="'	. $for . '" ';
		$label .= form_attributes($attributes);		
		$label .= '>' . $html . '</label>';
		return $label;
	}
}

/**
 * Crea un <input type="reset"> con los atributos solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_reset'))
{	
	function form_reset($attributes = array())
	{
		return form_default_input('reset', $attributes);
	}
}

/**
 * Crea un <button> con los atributos solicitados
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_button'))
{	
	function form_button($html = '',$attributes = array())
	{
		$button = '<button ';
		$button .= form_attributes($attributes);	
		$button .= '>' . $html . '</button>';
		return $button;		
	}
}

/**
 * Crea una etiqueta anchor, solo es necesario agregar en el href los segmentos despues
 * del index.phtml
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('anchor'))
{	
	function anchor($uri = '', $text = '', $attributes = array(), $external = FALSE)
	{
	    if (strpos($uri, '#') === FALSE)
	    {
            $uri = ( ! preg_match('!^\w+://! i', $uri)) ? get_url().((substr($uri,0,1) == '/') ? '' : '/' ).$uri : $uri;            
	    }
	    echo $uri;
        return '<a href="'.$uri.'" '.form_attributes($attributes).' >'.$text.'</a>';
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

/**
 * Muestra los errores de la validacion.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if ( ! function_exists('form_error'))
{	
	function form_error($field = '', $prefix = '', $suffix = '')
    {
        if (FALSE === ($OBJ = form_validation::getInstance()))
        {
            return '';
        }

        return $OBJ->error($field, $prefix, $suffix);
    }
}


if ( ! function_exists('validation_errors'))
{
	function validation_errors($prefix = '', $suffix = '')
	{
		if (FALSE === ($OBJ = form_validation::getInstance()))
		{
			return '';
		}

		return $OBJ->error_string($prefix, $suffix);
	}
}


/**
 * Escribe los valores dentro de un campo despues de una validacion
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('set_value'))
{   
    function set_value($field = '', $default = '')
    {
        if (FALSE === ($OBJ = form_validation::getInstance()))
        {
            if ( ! isset($_POST[$field]))
            {
                return $default;
            }
            return form_prep($_POST[$field], $field);
        }
        return form_prep($OBJ->set_value($field, $default), $field);
    }
}

/**
 * Ayuda a limpiar los datos para ser usados dentro de los formularios o html en general, 
 * por ejemplo dentro de un input:
 * 
 * <input type="text" value="&quot; texto &quot;" >
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('form_prep'))
{    
    function form_prep($str = '', $field_name = '')
    {
        static $prepped_fields = array();

        
        
        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = self::form_prep($val);
            }

            return $str;
        }

        if ($str === '')
        {
            return '';
        }

        if (isset($prepped_fields[$field_name]))
        {
            return $str;
        }
        
        $str = htmlspecialchars($str);
        $str = str_replace(array("'", '"'), array("&#39;", "&quot;"), $str);

        if ($field_name != '')
        {
            $prepped_fields[$field_name] = $str;
        }
        
        return $str;
    }
}	
	
	