<?php 

class Array_Sort {
    
    static private $sortfield = null;
    static private $sortorder = 1;
    
    static private function sort_object(&$a, &$b) 
    {
        if ( ! isset($a->{self::$sortfield})) return false;
        
        if (strtolower($a->{self::$sortfield}) == strtolower($b->{self::$sortfield})) return 0;
        
        return (strtolower($a->{self::$sortfield}) < strtolower($b->{self::$sortfield})? -self::$sortorder : self::$sortorder);
    }
    
    static private function _sort_array(&$a, &$b) 
    {
        if ( ! isset($a[self::$sortfield])) return false;
        
        if ($a[self::$sortfield] == $b[self::$sortfield]) return 0;
        
        return ($a[self::$sortfield] < $b[self::$sortfield]) ? -self::$sortorder : self::$sortorder;
    }
    
    static function sort(&$v, $field = NULL, $asc = TRUE, $type = 'object') 
    {
        if (is_null($field))
        {
            return FALSE;
        }
        
        self::$sortfield = $field;
        self::$sortorder = $asc ? 1 : -1;
        usort($v, array('Array_Sort', 'sort_'.$type));
    }
}