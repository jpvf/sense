<?php

class Breadcrumbs {

    private static $breadcrumbs = array();
    private static $separator   = '/';

    static function add($items = array(), $title = '')
    {
        if (empty($items))
        {
            return;
        }

        if (is_string($items))
        {
            if (empty($title))
            {
                $items = array($items);
            }
            else
            {               
                $items = array($items => $title);
            }
        }
        

        foreach ($items as $key => $val)
        {
            if (is_numeric($key))
            {
                self::$breadcrumbs[] = '<span>'.$val.'</span>';
            }
            else
            {
                self::$breadcrumbs[] = anchor($key, $val);
            }
        } 

    }

    static function generate()
    {
        $items     = self::$breadcrumbs;        
        $separator = '<div style="float:left"><div class="triangle-visible"></div><div class="triangle-transparent"></div></div>';
        $uri       = Uri::getInstance()->get_uri_string();        
        $home      = anchor(get_url(), 'Home');
                        
        array_unshift($items, $home);
        
        return "<div class='breadcrumbs'><ul><li>".implode($separator.'</li><li>', $items).'</ul></div>';        
    }

}