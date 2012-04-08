<?php 

class Template{
	
	private $content = array();
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
		
		$this->load = Loader::getInstance();		
	}

	function set_template($template = '')
	{
		if (empty($template))
		{
			return $this;
		}

		$this->template = trim($template, '/');
		return $this;
	}

	function set_content($place = '', $content = '')
	{
		if ($content == '' OR $place == '')
		{
			return $this;			
		}	

		$this->content[$place] = $content;
		return $this;
	}

	function get_content()
	{
		return $this->content;
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

	function render_partial($partial = '')
	{
		$this->load->view($this->template.'/partials/'.$partial.EXT, $this->data, false, APP_PATH.'themes/');
	}

	function render($data = array(), $return = FALSE)
	{
	    $this->_presenter_loaded AND $data[$this->_presenter_name] = $this->_presenter;
	    $this->data = $data;
	    
		if ( ! empty($this->content))
		{
		    foreach ($this->content as $place => $content)
            {
                $data['template_'.$place] = $this->load->view($content, $data, TRUE);    
            }
        } 

		return $this->load->view($this->template.'/index'.EXT, $data, $return, APP_PATH.'themes/');
	}

}