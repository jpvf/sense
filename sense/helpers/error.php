<?php 

/**
 * Muestra un error creado en la clase error.
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('throw_error'))
{ 
    function throw_error($type = '404')
    {
        $error = Sense\Core\Error::getInstance();
        $error->trigger_error($type);
        exit;
    }
}
/**
 * Muestra los errores con estilos
 *
 * @access  public
 * @param   string
 * @return  string
 */

if( ! function_exists('error_handler'))
{ 
    function error_handler($errno, $errstr, $errfile, $errline)
    {
        $levels = array(
                        E_ERROR             =>  'Error',
                        E_WARNING           =>  'Warning',
                        E_PARSE             =>  'Parsing Error',
                        E_NOTICE            =>  'Notice',
                        E_CORE_ERROR        =>  'Core Error',
                        E_CORE_WARNING      =>  'Core Warning',
                        E_COMPILE_ERROR     =>  'Compile Error',
                        E_COMPILE_WARNING   =>  'Compile Warning',
                        E_USER_ERROR        =>  'User Error',
                        E_USER_WARNING      =>  'User Warning',
                        E_USER_NOTICE       =>  'User Notice',
                        E_STRICT            =>  'Runtime Notice'
                    );
    
        echo '<div style="width:960px;margin:0 auto;padding:10px;background:#f7f7f7;border:1px solid #e5e5e5">

                <h3>PHP Error - '.$levels[$errno].'</h3>
                <p>'.$errstr.'</p>
                <p style="color:#666">Filename: ' . $errfile .'</p>
                <p style="color:#666">Line Number: ' . $errline .'</p>
                
              </div>';
        return TRUE;    
    }
}

function exceptions_handler($e)
{
    $data = array();
    $data['type']       = get_class($e);
    $data['severity']   = $e->getCode();
    $data['message']    = $e->getMessage();
    $data['filepath']   = $e->getFile();
    $data['error_line'] = $e->getLine();
    $data['backtrace']  = $e->getTrace();

    foreach ($data['backtrace'] as $key => $trace)
    {
        if ( ! isset($trace['file']))
        {
            unset($data['backtrace'][$key]);
        }
        elseif (strpos($trace['file'],'error'))
        {
            unset($data['backtrace'][$key]);
        }
    }
    ?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
    <style type="text/css">
        #container {width:960px;margin:0 auto;padding:10px;background:#f7f7f7;border:1px solid #e5e5e5}
    </style>
  </head>
  <body>
    <div id='container'>
                      <?php

    echo '<h3 style="display:inline">' . $data['type'] . '</h3> ' . $data['message'] . br();

    $append = array(
        'file' => $data['filepath'],
        'line' => $data['error_line']
    );
    
    array_unshift($data['backtrace'],$append);

    foreach ($data['backtrace'] as $generate)
    {
     
        $lines = file_lines($generate['file'], $generate['line']);
        echo '<style>';
        echo '.file-exception{list-style-type:none;padding:0;background:#272822;border:1px solid #000;margin:20px auto;}';
        echo '.file-exception li{clear:both;overflow:hidden;color:#fff}';
        echo '.file-exception span{display:inline-block}';
        echo '.exception-line{background:#272822;color:#8F908A;width:30px;float:left}';
        echo '.file-exception li.selected{background:#3E3D32 !important}';
        echo '.file-exception li.selected .exception-line{background:#3E3D32 !important}';
        echo '.file{text-align:center;font-size:0.9em}';
        echo '</style>';
        echo '<pre><ul class="file-exception">';

        echo '<li class="file">' . $generate['file'] . '</li>';
        foreach ($lines as $line => $content)
        {
            $selected = '';

            if ($line == $generate['line'])
            {
                $selected = 'class="selected"';
            }

            $content = str_ireplace('007700', 'F92672', $content);
            $content = str_ireplace('0000BB', 'FFFFFF', $content);
            $patterns = array();

            $patterns[] = '/<span style="color: #F92672">{+.*?<\/span>/';       
            $patterns[] = '/<span style="color: #F92672">}+.*?<\/span>/';
            $patterns[] = '/\(/';       
            $patterns[] = '/\)/';
            $patterns[] = '/\[/';       
            $patterns[] = '/\]/';
            $patterns[] = '/(\'[\']+.*?\')/';
            $patterns[] = '/<span style="color: #F92672">;<\/span>/';
                     
            $subs = array();
            $subs[] = '<span style="color: #FFF">{</span>';
            $subs[] = '<span style="color: #FFF">}</span>';
            $subs[] = '</span><span style="color: #FFF">(</span>';
            $subs[] = '</span><span style="color: #FFF">)</span>';        
            $subs[] = '</span><span style="color: #FFF">[</span>';
            $subs[] = '</span><span style="color: #FFF">]</span>';
            $subs[] = '</span><span style="color: #E6DB67">${1}</span>';
            $subs[] = '<span style="color: #FFFFFF">;</span>';   
            $content = preg_replace($patterns, $subs, $content);

            $content = str_replace('#000000', '#FFFFFF', $content);
            $content = str_replace('#DD0000', '#E6DB67', $content);

            if (strpos($content,'function') !== FALSE)
            {
                $content = str_replace('function', '</span><span style="color:#66D9D0">function</span>', $content);
            }

            if (strpos($content,'array') !== FALSE)
            {
                $content = preg_replace('/[^_]array/', '</span><span style="color:#66D9D0">array</span>', $content); 
            }

            if (strpos($content, '//') !== FALSE)
            {
                $content = preg_replace('/#[a-zA-Z0-9].*?\"/i', '#8F908A"', $content);
                $selected .= ' style="color:#8F908A !important"';
            }
            echo "<li $selected><span class='exception-line'>$line</span><span>$content</span></li>";
        }
        echo '</ul></pre>';
       
    }

    ?>
    </div><!-- end#container -->
  </body>
</html>
<?php
}

    function file_lines($filepath, $line_num, $highlight = true, $padding = 5)
    {
        // We cache the entire file to reduce disk IO for multiple errors
        if ( ! isset($files[$filepath]))
        {
            $files[$filepath] = file($filepath, FILE_IGNORE_NEW_LINES);
            array_unshift($files[$filepath], '');
        }

        $start = $line_num - $padding;
        if ($start < 0)
        {
            $start = 0;
        }

        $length = ($line_num - $start) + $padding + 1;
        if (($start + $length) > count($files[$filepath]) - 1)
        {
            $length = NULL;
        }

        $debug_lines = array_slice($files[$filepath], $start, $length, TRUE);

        if ($highlight)
        {
            $to_replace = array('<code>', '</code>', '<span style="color: #0000BB">&lt;?php&nbsp;', "\n");
            $replace_with = array('', '', '<span style="color: #0000BB">', '');

            foreach ($debug_lines as & $line)
            {
                $line = str_replace($to_replace, $replace_with, highlight_string('<?php ' . $line, TRUE));
            }
        }

        return $debug_lines;
    }