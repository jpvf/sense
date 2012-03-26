<?php 

/**
 * 
 * Verifica si el valor que llega es NULL o no numérico
 */
function check_value($value = NULL, $check_numeric = TRUE)
{
    $error = FALSE;
    
    if (is_null($value))
    {
        $error = TRUE;        
    }    

    if ($check_numeric === TRUE)
    {
        if ( ! is_numeric($value))
        {
            $error = TRUE;        
        }
    }
    
    if ($error === TRUE)
    {
        mostrar_error();
    }
    
    return TRUE;
}