<?php 

class Template{
	
	private $title;
	private $submenu;
	private $section_title;
	private $section;
	private $content;
	private $inner_menu;
	private $content_header;
	public  $menu = TRUE;
	private static $instance;
	
	private $_presenter_loaded = FALSE;
	private $_presenter;
	private $_presenter_name;
	
	public static function getInstance()
	{
	    if ( ! self::$instance)
	    {
	        self::$instance = new Template;
	    }	    
	    return self::$instance;
	}
	

	function __construct()
	{
		Config::load('template');
		
		$template = Config::get_group('template');
		
		foreach ($template as $key => $val)
		{
			$this->$key = $val;
		}
		
		$this->load = loader::getInstance();		
	}

	function set_template($template = '')
	{
		if (empty($template))
		{
			return $this;
		}

		$this->template = $template;
		return $this;
	}

	function set_title($title = '')
	{
		$this->title = ($title == '') ? $this->config->title : $title;
		return $this;
	}

	function get_title()
	{
		return $this->title;		
	}

	function set_section_title($section_title = '')
	{
		$this->section_title = $section_title;
		return $this;
	}

	function get_section_title()
	{
		return $this->section_title;
	}

	function set_submenu($submenu = '')
	{
		if ($submenu == '')
		{
			return $this;	
		}

		$this->submenu = $submenu;
		return $this;		
	}
	
    function set_innermenu($inner_menu = '')
    {
        if ($inner_menu == '')
        {
            return $this; 
        }

        $this->inner_menu = $inner_menu;
        return $this;       
    }
    
    function get_innermenu()
    {
        if ( ! empty($this->inner_menu)) 
        {
            return "<div id='notifications-menu' style='margin-bottom: 10px; overflow: hidden;'>
                            {$this->inner_menu}
                       </div>";
        }

        return;
    }

	function get_submenu()
	{
		if ( ! empty($this->submenu)) 
		{
			return "<div id='secondary-menu-wrapper'>
          					{$this->submenu}
        			   </div>";
		}

		return;
	}

	function set_section($section = '')
	{
		if ($section == '')
		{
			return;			
		}
		
		$this->section[] = $section;
		return $this;
	}

	function get_section()
	{
		if ( ! empty($this->section))
		{
			return "<td id='col-right'>
                	{$this->section}
            		</td>";
		}

		return;
	}

	function get_copyright()
	{
		return $this->copyright;
	}

	function set_content($content = '')
	{
		if ($content == '')
		{
			return $this;			
		}	

		$this->content[] = $content;
		return $this;
	}

	function get_content()
	{
		return $this->content;
	}
	
	function set_content_header($content = '')
	{
		if ($content == '')
		{
			return $this;			
		}	

		$this->content_header = $content;
		return $this;
	}
	
    function get_content_header()
	{
		return $this->content_header;
	}

	function menu($menu = TRUE)
	{
		$this->menu = $menu;
		return $this;
	}

	function build_json($data = array())
	{
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);
		exit;
	}
	
	function presenter($presenter = '', $data = array())
	{
	    $this->load->presenter($presenter);
	    
	    if (strpos($presenter, '/') !== FALSE)
	    {
	        list($directorio, $presenter) = explode('/', $presenter);
	    }
	    
	    $this->_presenter_name = str_ireplace('_presenter', '', $presenter);
	    $this->_presenter = new $presenter($data);
	    $this->_presenter_loaded = TRUE;
	    
	    return $this;
	}

	function render($data = array(), $return = FALSE)
	{
	    $this->_presenter_loaded AND $data[$this->_presenter_name] = $this->_presenter;
	    
		if ( ! empty($this->content))
		{
		    $all_content = '';		    
		    foreach ($this->content as $content)
            {
                $all_content .= $this->load->view($content, $data, TRUE);    
            }
            $this->content = $all_content;
        } 
        
        if ( ! empty($this->content_header))
        {
            $this->content_header = $this->load->view($this->content_header, $data, TRUE);
        }

		if ( ! empty($this->submenu))
		{
        	$this->submenu = $this->load->view($this->submenu, $data, TRUE);
        }
        
	    if ( ! empty($this->inner_menu))
        {
            $this->inner_menu = $this->load->view($this->inner_menu, $data, TRUE);
        }

        if ( ! empty($this->section))
		{
		    $all_sections = '';
		    
		    foreach ($this->section as $section)
		    {
		      $all_sections .= $this->load->view($section, $data, TRUE);    
		    }
        	$this->section = $all_sections;
        }
        
        if (is_xhr())
        {
        	$template = array('content' => $this->content,
        					  'submenu' => $this->get_submenu(),
        					  'section' => $this->section);
        	echo json_encode($template);
        	exit;
        }

		$this->load->view($this->template, $data, $return);
	}

}