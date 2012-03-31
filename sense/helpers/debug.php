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
    function debug()
    {
        $args = func_get_args();

        foreach ($args as $arg)
        {
            echo '<pre>';
            print_r($arg);
            echo '</pre>';
        }
    }
}


if( ! function_exists('backtrace'))
{
    function backtrace()
    {
        $output = "<fieldset class='backtrace'><legend><h2>Backtrace</h2></legend>";
        $backtrace = debug_backtrace();
    
        foreach ($backtrace as $bt) 
        {
            $args = array();

            foreach ($bt['args'] as $a) {
                
                switch (gettype($a)) 
                {
                    case 'integer':
                    case 'double':
                        $args []= $a;
                        break;
                    case 'string':
                        $a = htmlspecialchars(substr($a, 0, 64)).((strlen($a) > 64) ? '...' : '');
                        $args []= "\"$a\"";
                        break;
                    case 'array':
                        $args []= 'Array('.count($a).')';
                        break;
                    case 'object':
                        $args []= 'Object('.get_class($a).')';
                        break;
                    case 'resource':
                        $args []= 'Resource('.strstr($a, '#').')';
                        break;
                    case 'boolean':
                        $args []= $a ? 'True' : 'False';
                        break;
                    case 'NULL':
                        $args []= 'Null';
                        break;
                    default:
                        $args []= 'Unknown';
                }
            }
            
            $line     = isset($bt['line'])     ? $bt['line']     : '';
            $file     = isset($bt['file'])     ? $bt['file']     : '';
            $class    = isset($bt['class'])    ? $bt['class']    : '';
            $type     = isset($bt['type'])     ? $bt['type']     : '';
            $function = isset($bt['function']) ? $bt['function'] : '';
            
            $output .= "
                <div class='backtrace-div'>
                    <h3>$file&nbsp;</h3>
                    <p><strong>line:</strong> $line</p>
                    <p><strong>call:</strong> $class $type $function(".implode(', ', $args).")</p>
                </div>
            ";
        }
        $output .= "</fieldset>\n
        <style>
            .backtrace div{border:1px solid #e5e5e5;margin:0px 0px 10px;}
            .backtrace div, .backtrace p {margin: 0 0 9px; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; line-height: 18px}
            .backtrace h3 {padding:10px;margin:0px;background:#f7f7f7}
            .backtrace p {padding:10px}
        </style>
        ";
        return $output;
    }
}