<?php 
/**
 * Debug de arrays o variables mostrando donde se encuentra la variable
 * y otras opciones.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('dump'))
{
    function dump()
    {
        list($callee) = debug_backtrace();
        $arguments = $callee['args'];
        $total_arguments = count($arguments);
    
        echo '<fieldset style="background: #fefefe !important; border:2px red solid; padding:5px">';
        echo '<legend style="background:lightgrey; padding:5px;display:block !important">'.$callee['file'].' @ line: '.$callee['line'].'</legend><pre>';
    
        $i = 0;
        foreach ($arguments as $argument)
        {
            echo '<br/><strong>Debug #'.(++$i).' of '.$total_arguments.'</strong>: ';
            var_dump($argument);
        }
    
        echo "</pre>";
        echo "</fieldset>";
    }    
}

/**
 * Debug de arrays o variables sencillo
 *
 * @access  public
 * @param   string
 * @return  string
 */
if( ! function_exists('debug'))
{
    function debug($array, $die = FALSE)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        if($die === TRUE){
          die();
        }
    }
}

/**
 * Escribe en un archivo los errores de mysql.
 *
 * @access  public
 * @param   string
 * @return  string
 */
if( ! function_exists('log_mysql'))
{
  function log_mysql($query, $error){
        $archivo = RUTA_LOGS . "log.txt";
        $fp = fopen($archivo, 'a');
        fputs($fp, "\n---------------------------------------------------------------------------Date " . date("d-m-Y h:i:s a ") ."-----------------------------------------\n");
        fputs($fp, "\n Query: \n\n\t$query  \n\n Error: $error  \n\n File: \t" . $_SERVER['REQUEST_URI'] . "\n");
        fputs($fp, "\n");
        fclose($fp);
    }
}


  function backtrace()
    {
        $output = "<div style='text-align: left;'>";
        $output .= "<b>Backtrace:</b><br />\n";
        $backtrace = debug_backtrace();
    
        foreach ($backtrace as $bt) {
            $args = '';
            foreach ($bt['args'] as $a) {
                if (!empty($args)) {
                    $args .= ', ';
                }
                switch (gettype($a)) {
                case 'integer':
                case 'double':
                    $args .= $a;
                    break;
                case 'string':
                    $a = htmlspecialchars(substr($a, 0, 64)).((strlen($a) > 64) ? '...' : '');
                    $args .= "\"$a\"";
                    break;
                case 'array':
                    $args .= 'Array('.count($a).')';
                    break;
                case 'object':
                    $args .= 'Object('.get_class($a).')';
                    break;
                case 'resource':
                    $args .= 'Resource('.strstr($a, '#').')';
                    break;
                case 'boolean':
                    $args .= $a ? 'True' : 'False';
                    break;
                case 'NULL':
                    $args .= 'Null';
                    break;
                default:
                    $args .= 'Unknown';
                }
            }
            
            $line = isset($bt['line']) ? $bt['line'] : '';
            $file = isset($bt['file']) ? $bt['file'] : '';
            $class = isset($bt['class']) ? $bt['class'] : '';
            $type = isset($bt['type']) ? $bt['type'] : '';
            $function = isset($bt['function']) ? $bt['function'] : '';
            
            $output .= "<br />\n";
            $output .= "<b>file:</b> $file<br>";
            $output .= "<b>line:</b> $line<br>";
            $output .= "<b>call:</b> $class $type $function($args)<br>";
        }
        $output .= "</div>\n";
        return $output;
    }