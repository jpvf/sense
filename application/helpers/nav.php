<?php 

class breadcrumbs
{

	public function __construct()
	{
		$this->separator   = ' > ';
		$this->home 	   = 'Home';
		$this->home_url	   = 'index.phtml';
		$this->project	   = '';
		$this->section     = '';
		$this->action	   = '';
		$this->option	   = '';
		$this->project_url = '';
		$this->section_url = '';
		$this->action_url  = '';
		$this->option_url  = '';
		$this->uri 		   = uri::getInstance();		
	}
	
	public function home($home = '', $url = '')
	{
		if(trim($home) != ''){
			$this->home = $home;
		}
		if(trim($url) != ''){
			$this->home_url = $url;
		}
	}

	public function project($project = '')
	{
		if(trim($project) != ''){
			$this->project = $project;
		}
		if(trim($url) != ''){
			$this->project_url = $url;
		}
	}
	
	public function section($section = '')
	{
		if(trim($section) != ''){
			$this->section = $section;
		}
		if(trim($url) != ''){
			$this->section_url = $url;
		}
	}
	
	public function action($action = '')
	{
		if(trim($action) != ''){
			$this->action = $action;
		}
		if(trim($url) != ''){
			$this->action_url = $url;
		}
	}
	
	public function option($option = '')
	{
		if(trim($option) != ''){
			$this->option = $option;
		}
		if(trim($url) != ''){
			$this->option_url = $url;
		}
	}
	
	public function separator($separator = '')
	{
		if(trim($separator) != ''){
			$this->separator = $separator;
		}
	}
	
	public function first_segment(){
		if(!$this->uri->get(1)){
			$breadcrumb = DASHBOARD;
		}else{
			$breadcrumb = ucfirst($this->uri->get(1));
		}
		return $breadcrumb;
	}
	
	public function second_segment(){
		$breadcrumb = str_ireplace('_',' ',ucfirst($this->uri->get(2)));
		return $breadcrumb;
	}
	
	public function third_segment(){
		$breadcrumb = str_ireplace('_',' ',ucfirst($this->uri->get(3)));
		return $breadcrumb;
	}
	
	public function fourth_segment(){
	    if($this->uri->get(3) == 'tickets'){
	        if(is_numeric($this->uri->get(4))){
	           $breadcrumb = ucwords(get_ticket($this->uri->get(4),'title'));
	        }else{
	           $breadcrumb = str_ireplace('_',' ',ucfirst($this->uri->get(4)));
	        }	        
	    }else{
		  $breadcrumb = str_ireplace('_',' ',ucfirst($this->uri->get(4)));
	    }
		return $breadcrumb;
	}
	
	public function generate_breadcrumb()
	{
		$this->breadcrumb = anchor('','Inicio');
		$this->breadcrumb .= $this->separator;
		if(!is_numeric($this->uri->get(3))){
			if($this->uri->get(2) AND $this->uri->get(2) != 'index'){
				$this->breadcrumb .= anchor('/' . $this->uri->get(1), $this->first_segment());
				$this->breadcrumb .= $this->separator;
				$this->breadcrumb .= '<span>' . $this->second_segment() . '</span>';
			}else{
				$this->breadcrumb .= '<span>' . $this->first_segment() . '</span>';			
			}
		}else{
			$this->breadcrumb .= anchor('/' . $this->uri->get('1') , $this->first_segment() );
			$this->breadcrumb .= $this->separator;
			$this->breadcrumb .= anchor('/' .  $this->uri->get('1') . '/' .  $this->uri->get('2') . '/' .  $this->uri->get('3'), nombre_operacion());
		}
		return $this->breadcrumb;
	}
	
}