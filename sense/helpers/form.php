<?php 



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
	
	