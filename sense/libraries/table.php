<?php 

class Table
{
	private static $table;
	
	private $headings_tag = 'td';
	
    private $table_head = array();     
    private $table_rows = array();
    private $table_foot = array();
    private $thead_class= array();
    private $tbody_class= array();
    private $tfoot_class= array();
    private $generated  = FALSE;
    private $table_final;
	
	
	public static function getInstance()
	{
		if ( ! self::$table)
		{
			self::$table = new table();
		}
		return self::$table;
	}
	
	public function __construct()
	{
		
	}
    
    private function _create_row($data, $attr = array(), $tag = 'td')
    {
        $row = '';
        
        if (is_string($data))
        {
           if (strpos(',', $data) !== FALSE)
           {
               $data = explode(',', $data);
           }
        }
        
        if (count($data) > 0)
        {           
            $attrs = array();
             
            if ( ! empty($attr))
            {
                foreach ($attr as $key => $val)
                {
                    $attrs[] = "$key='$val'";
                }   
            }
            $row .= "\n\t\t<tr ".implode(' ',$attrs).">";
            
                foreach($data as $td)
                {
                    $td_attr = '';
                    
                     if (strpos($td, '|') !== FALSE)
                     {
                        list($exploded_data, $td_attr) = explode('|', $td);                      
                        $td = $exploded_data;                    
                     }
                     
                     $row .= "\n\t\t\t<$tag $td_attr>$td</$tag>";
                }
                
            $row .= "\n\t\t</tr>";
        }
        
        return $row;
    }
    
    private function reset_table()
    {
        $this->table_head = array();
        $this->table_rows = array();
        $this->table_foot = array();
    }
	
	public function add_heading($data = array(), $attr = array(), $tag = 'td')
	{
	    $this->table_head[] = $this->_create_row($data, $attr, $tag);		
		return $this;
	}
	
	public function add_row($data = array(), $attr = array())
	{
	    $this->table_rows[] = $this->_create_row($data,$attr);		
        return $this;
	}
	
	public function add_foot($data = array(), $attr = array())
	{
	    $this->table_foot[] = $this->_create_row($data,$attr);     
        return $this;
	}
	
	public function from_results($data = array(), $empty_message = '', $head_from_fields = FALSE, $show = array())
	{
	    $this->generated      = TRUE;	 
	    $this->data           = $data;
	    $this->empty_message  = $empty_message;
	    $this->headers        = $head_from_fields;
	    $this->fields_to_show = $show;
	       
	    return $this;
	}
	
	private function _generate_from_results()
	{
	    $data             = $this->data;
	    $empty_message    = $this->empty_message;
        $head_from_fields = $this->headers;
        $show             = $this->fields_to_show;
	    
	    $keys_show = array();
        $head = array();        
                
        $fields = db::getInstance()->field_data('', $data);          
           
        foreach ($fields as $field)
        {
            if ( ! empty($show))
            {                 
                if (in_array($field->name, $show)) 
                { 
                    $head[] = ucwords(str_replace('_', ' ', $field->name));
                    $keys_show[$field->name] = $field->name;
                }
            }
            else
            {                   
                $head[] = ucwords(str_replace('_', ' ', $field->name));
            }
        }
            
        if ($head_from_fields !== FALSE)
        {     
            $this->add_heading($head);
        }
        
        if ($data->num_rows() > 0)
        {
            foreach ($data->result() as $val)
            {
                if ( ! empty($show))
                {
                    $copy = $val;
                    $copy = array_intersect_key((array) $copy, $keys_show);
                    $this->add_row((array) $copy);
                    $this->total_columns = count($copy);
                    $this->columns       = array_keys($copy);                   
                }
                else 
                {
                    $this->add_row((array) $val);
                    $this->total_columns = count($val);
                    $this->columns       = array_keys($val);
                }
            }
        }
        else 
        {
           $this->add_row($empty_message);   
        }
	}
	
	public function filters()
	{	    
	    $filters = array();
	    
	    for ($i = 0; $i < $this->total_columns; $i++)
	    {
	        $filters[] = Form::text(array('name' => $this->columns[$i]));
	    }
	    
	    $filters = array($this->_create_row($filters, array()));
        $this->table_rows = array_merge($filters,$this->table_rows);
        
        return $this;
	}
	
	public function thead_class($class = NULL)
	{
	    $this->_add_class($class, 'thead');
	    return $this;
	}
	
    public function tbody_class($class = NULL)
    {
        $this->_add_class($class, 'tbody');
        return $this;
    }
    
    public function tfoot_class($class = NULL)
    {
        $this->_add_class($class, 'tfoot');        
        return $this;
    }
    
    private function _add_class($class = NULL, $add_to = '')
    {
        if ( ! is_null($class))
        {
            $add_to .= '_class';
            $this->{$add_to}[] = $class;
        }
        
        return $this;
    }
	
	private function _get_class($item = NULL)
	{
	    if ( ! empty($this->thead_class) AND ! is_null($item))
	    {
	        $item .= '_class';
	        return ' class="'.implode(' ', $this->$item).'"';
	    }
	    
	    return '';
	}
	
	public function generate($attributes = array())
	{
		if (is_array($attributes))
		{
			$attrs = array();
			
			foreach ($attributes as $key => $val)
			{
				$attrs[] = "$key='$val'";
			}		
			
			$attrs = implode(' ', $attrs);
		}
		else 
		{
			$attrs = $attributes;
		}
		
		if ($this->generated === TRUE)
		{
		    $this->_generate_from_results();
		}
		
		$thead_class = $this->_get_class('thead');
		$tfoot_class = $this->_get_class('tfoot');
		$tbody_class = $this->_get_class('tbody');
			
		$table  = "<table ".$attrs.">";
		$table .= "\n\t<thead{$thead_class}>".implode("", $this->table_head)."\n\t</thead>";
		$table .= "\n\t<tbody{$tbody_class}>".implode("", $this->table_rows)."\n\t</tbody>";
		$table .= "\n\t<tfoot{$tfoot_class}>".implode("", $this->table_foot)."\n\t</tfoot>";
		$table .= "\n</table>\n\n";
        
		$this->reset_table();
		
		return $table;
	}	

}

class Grids extends Table {
    
    private $rows = 0;

    function add_columns($columns = array())
    {        
        $i = 0;
        foreach ($columns as $column)
        {
            $columns[$i] = '<div class="grid-column-wrapper"><span class="title">'.$column.'</span><span class="ui-icon ui-icon-triangle-2-n-s align-right"></span></div>';
            $i++;   
        }
                
        $check = Form::checkbox(array(
            'class' => 'check-all'
        ));
        
        array_unshift($columns, '<div class="grid-column-wrapper" style="padding-left: 0 !important;">'.$check.'</div>|class="grid-check"');
        
        $this->add_heading($columns);
        return $this;
    }
    
    function add($row = array())
    {
        $this->rows++;
        
        if (isset($row['id']))
        {
            $check = Form::checkbox(array(
                'name' => 'action[]',
                'value'=> $row['id']
            ));
            
            array_unshift($row, $check . '|class="grid-check" style="padding-left: 0 !important;"');
            unset($row['id']);
        }
        $this->add_row($row);
    }
    
    function render($attrs = array())
    {
        $this->thead_class('')
             ->tbody_class('')
             ->tfoot_class('');
        
         $style = <<<STYLE
    <style>
    .grid thead span.title{color:#666;cursor:default}    
    .grid thead span.ui-icon{cursor:pointer}    
    .grid thead td{background:#f1f1f1;border: 1px solid #dfdfdf !important;padding:0px !important}
    
    .grid tbody td{border-left: 1px solid #dfdfdf !important;border-right: 1px solid #dfdfdf !important;padding-left:15px !important;}
    .grid tbody tr{border-top:none;border-bottom:1px solid #dfdfdf}    
    
    .grid .grid-check{text-align:center;width:36px;}
    .grid .grid-check input{margin:0}
    .grid .grid-row-selected{background:#FFFFD7}
    .grid .grid-row-hover{background: #F7F7F7;}
    .grid .grid-column-wrapper{border:1px solid #fff;padding:10px 0px 10px 15px}
    </style>         
STYLE;
             
        $attrs['class'] = 'table-full grid';
        Assets::js('jquery.fixedtable');
        Assets::js('grid');
        return $this->generate($attrs).$style;
    }

}