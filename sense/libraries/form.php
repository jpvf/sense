<?php

class Form {

    private static function attributes($attrs = array())
    {
        if ( ! is_array($attrs))
        {
            return ' '.$attrs.' ';
        }

        $form_attrs = array();

        foreach ($attrs as $key => $val)
        {
            $form_attrs[] = "$key='$val'";
        }

        return ' '.implode(' ', $form_attrs);
    }
    
    public static function open($action = '', $attrs = array())
    {
        if (is_array($attrs))
        {
            if (is_string($action))
            {
                $attrs['action'] = (empty($action) ? '' : get_url().'/'.$action);    
            }
            else 
            {
                $attrs = $action;
            }
        
            if ( ! array_key_exists('method', $attrs))
            {
                $attrs['method'] = 'post';
            }
        } 
        else
        {
            $action = (empty($action) ? '' : get_url().'/'.$action); 
            $attrs = 'action = "'.$action.'" '.$attrs;
            if ( ! preg_match('/method([\s]|)(\'|\")([\s]|)(get|post)([\s]|)(\'|\")([\s]|)/i', $attrs))
            {
                $attrs .= 'method="post"';
            }
        }

        return '<form'.self::attributes($attrs).'>';
    }
    

    public static function multipart($action = '', $attrs = array())
    {
        if (is_array($attrs))
        {;    
            $attrs['enctype'] = "multipart/form-data";
        }
        else 
        {
        	$attrs .= ' enctype="multipart/form-data"';
        }
        
        return self::open($action, $attrs);
    }

    public static function close()
    {
        return '</form>';
    }

    public static function text($attrs = array())
    {
        return self::input('text', $attrs);
    }
    
    public static function password($attrs = array())
    {
        return self::input('password', $attrs);
    }
    
    public static function checkbox($attrs = array())
    {
        if (isset($attrs['checked']) AND $attrs['checked'] === FALSE)
        {
            unset($attrs['checked']);
        }
        return self::input('checkbox', $attrs);
    }
    
    public static function submit($value = '', $attrs = array())
    {
        if (is_string($value))
        {
            if (is_array($attrs))
            {
                $attrs['value'] = $value;
            }
            else 
            {
                $attrs .= ' value="'.$value.'"';
            }
        }
        
        return self::input('submit', $attrs);
    }
    
    public static function radio($attrs = array())
    {
        return self::input('radio', $attrs);
    }
        
    public static function hidden($attrs = array())
    {
        return self::input('hidden', $attrs);
    }
    
    public static function file($attrs = array())
    {
        return self::input('file', $attrs);
    }
    
    public static function reset($value = '', $attrs = array())
    {
        if (is_string($value))
        {
            $attrs['value'] = $value;
        }
        
        if (is_array($value))
        {
            $attrs = $value;
        }
        
        return self::input('reset', $attrs);
    }
    
    private static function input($type = 'text', $attrs = array())
    {                
        if (is_array($attrs))
        {
            if (isset($attrs['type']))
            {
                unset($attrs['type']);
            }
            
            $attrs['type'] = $type;
        }
        else 
        {
            $attrs = 'type="'.$type.'" '.$attrs;    
        }
        
        return '<input'.self::attributes($attrs).'/>';
    }
    
    public static function label($text = '', $for = '', $attrs = array())
    {
        if ($for != '')
        {
            $attrs['for'] = $for;   
        }
                
        return '<label'.self::attributes($attrs).'>'.$text.'</label>';        
    }
    
    public static function button($text = '', $attrs = array())
    {
        return '<button'.self::attributes($attrs).'>'.$text.'</button>';        
    }
    
    public static function textarea($value = '', $attrs = array())
    {
        $text = '';
        
        if (is_string($value))
        {
            $text = $value;
        }
        
        if (is_array($value))
        {
            $attrs = $value;
            if (isset($attrs['value']))
            {
                $text  = $attrs['value'];
                unset($attrs['value']);
            }
        }
        
        return '<textarea'.self::attributes($attrs).'>'.$text.'</textarea>';        
    }
    
    public static function select($items = array(), $attrs = array(), $item_selected = '')
    {
        $options = array();
        
        foreach ($items as $key => $val)
        {
            if (is_array($val))
            {
                $opt_options = array();
                
                foreach ($val as $k => $v)
                {
                    $selected = '';
                    
                    if ($k == $item_selected AND $item_selected != '')
                    {
                        $selected = ' selected="selected"';    
                    }
            
                    $opt_options[] = "<option value='$k'$selected>$v</option>"; 
                }
                
                $options[] = "<optgroup label='$key'>".implode('',$opt_options)."</optgroup>";
            }
            else
            {
                $selected = '';
                
                if ($key == $item_selected AND $item_selected != '')
                {
                    $selected = ' selected="selected"';    
                }
                
                $options[] = "<option value='$key'$selected>$val</option>"; 
            }
        }
        
        return '<select '.self::attributes($attrs).'>'.implode('',$options).'</select>';
    }
    
    public static function buttons($button_name = '', $cancel_name = '', $cancel_url)
    {
        $buttons = self::button($button_name, array('class' => 'btn btn-success','type' => 'submit')).' ó ';
        $buttons .= anchor($cancel_url, $cancel_name);
        
        return $buttons;
    }
}