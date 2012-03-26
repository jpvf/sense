<?php 

function selected_item($uri_segment = 0, $str = '')
{
    if ($uri_segment === 0)
    {
        return FALSE;
    }
    
    $uri = uri::getInstance();
    
    $return = ($uri->get($uri_segment) == $str)?' class="selected"':'';
    return  $return;
}
