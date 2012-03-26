<?php 

function get_username($first = '', $last = '')
{
    if (is_object($first))
    {        
        $last  = isset($first->last_name)  ? $first->last_name  : '';
        $first = isset($first->first_name) ? $first->first_name : '';
    }
    return $first.' '.$last;      
}