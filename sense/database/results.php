<?php 

namespace Sense\Database;

class Results
{
    
    var  $conn_id;
    var  $results_id;
    
    function __construct()
    {
        $this->result_object = array();
        $this->result_array  = array();
        $this->result_assoc  = array();
    }
    
    function result($type = 'object')
    {       
        if ($type == 'object')
        {
          return $this->_results_object();
        }
        
        if ($type == 'assoc')
        {
           return $this->_results_assoc();
        } 
        
        if ($type == 'array')
        {
           return $this->_results_array();
        }
        else
        {
           return array();
        }
    }

    function row($row = 0, $type = 'object')
    {        
        if( ! is_numeric($row))
        {
            return set_type(array(), $type);
        }     
        
        $results = $this->result($type); 

        if (key_exists($row, $results))
        {
            return $results[$row];
        }
        
        return array();
    }

    function get_fields($offset = FALSE)
    {
        if ($offset === FALSE)
        {
            $i = 0;
            $fields = array();
            
            while ($i < mysql_num_fields($this->results_id)) 
            {
                $fields[] = mysql_fetch_field($this->results_id, $i);
                $i++;
            }
            
            return $fields;
        }
        return mysql_fetch_field($this->results_id,$offset);
    }
    
    function num_fields()
    {
        return mysql_num_fields($this->results_id);
    }
    
       
    function num_rows()
    {
        return mysql_num_rows($this->results_id);
    }
    
    private function _results_array()
    {       
        if (count($this->result_array) > 0)
        {
            return $this->result_array;
        }
        
        while ($row = mysql_fetch_array($this->results_id))
        {
            $this->result_array[] = $row;
        }

        return $this->result_array;
    }
    
    private function _results_assoc()
    {       
        if (count($this->result_assoc) > 0)
        {
            return $this->result_assoc;
        }
        
        while ($row = mysql_fetch_assoc($this->results_id))
        {
            $this->result_assoc[] = $row;
        }

        return $this->result_assoc;
    }
    
    private function _results_object()
    {       
       if (count($this->result_object) > 0)
       {
            return $this->result_object;
       }
       
       while ($row = mysql_fetch_object($this->results_id))
       {
        $this->result_object[] = $row;
       }

       return $this->result_object;      
    }
           
}