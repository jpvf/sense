<?php 
/**
 * Clase para paginar datos
 * 
 * @version 2.0
 * @author Juan Pablo Velandia Fraija
 */
class Pagination {
    
    /**
	 * @var	integer	Número de la página actual
	 */
	public static $current_page = null;

	/**
	 * @var	integer	El índice en donde empiezan los registros de la página actual
	 */
	public static $offset = 0;

	/**
	 * @var	integer	Número de items por página
	 */
	public static $per_page = 10;

	/**
	 * @var	integer	Número total de páginas
	 */
	public static $total_pages = 0;

	/**
	 * @var array Variables para armar el html de la paginación
	 */
	public static $template = array(
    	'wrapper_start'  => '<div class="pagination"><ul class="pull-right"> ',
    	'wrapper_end'    => ' </ul></div>',
    	'page_start'     => '',
    	'page_end'       => '',
    	'previous_start' => '<li class="previous">&rarr; ',
    	'previous_end'   => ' </li>',
    	'previous_mark'  => '&laquo; ',
    	'next_start'     => '<li class="next"> ',
    	'next_end'       => ' &larr;</li>',
    	'next_mark'      => ' &raquo;',
    	'active_class'   => 'active',
        'item_start'	 => '<li>',
        'item_end'		 => '</li>'
    );

	/**
	 * @var	integer	Total de items que se muestran
	 */
	public static $total_items = 0;

	/**
	 * @var	integer	Total de links que se muestran
	 */
	protected static $num_links = 5;

	/**
	 * @var	mixed	Url de los links de paginación
	 */
	protected static $url;
    
	/**
	 * Init
	 *
	 * Carga la configuración de la paginación
	 *
	 * @access	public
	 * @return	void
	 */
	public static function init(array $params)
	{
		Config::load('pagination');
		$config = Config::get_group('pagination');
		
		$config = array_merge($params, $config);
				
		foreach ($config as $key => $value)
		{
			if ($key == 'template')
			{
				static::$template = array_merge(static::$template, $config['template']);
				continue;
			}

			static::${$key} = $value;
		}

		static::$total_pages = ceil(static::$total_items / static::$per_page) ?: 1;

		static::$current_page = (int) _get('page')?: 1;

		if (static::$current_page > static::$total_pages)
		{
			static::$current_page = static::$total_pages;
		}
		elseif (static::$current_page < 1)
		{
			static::$current_page = 1;
		}

		static::$offset = (static::$current_page - 1) * static::$per_page;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Genera el html completo de la paginación
	 *
	 * @access public
	 * @return mixed    HTML de la paginación
	 */
	public static function create_links()
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		$pagination  = static::$template['wrapper_start'];
		$pagination .= static::prev_link('Anterior');
		$pagination .= static::page_links();
		$pagination .= static::next_link('Siguiente');
		$pagination .= static::$template['wrapper_end'];

		return $pagination;
	}

	// --------------------------------------------------------------------

	/**
	 * Números de la paginación
	 *
	 * @access public
	 * @return mixed    HTML del los links con los números de paginación
	 */
	public static function page_links()
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		$pagination = '';

		// Número inicial de la paginación
		$start = ((static::$current_page - static::$num_links) > 0) ? static::$current_page - (static::$num_links - 1) : 1;

		// Número final de la paginación
		$end   = ((static::$current_page + static::$num_links) < static::$total_pages) ? static::$current_page + static::$num_links : static::$total_pages;

		for($i = $start; $i <= $end; $i++)
		{
		    $url = static::get_query_string($i);
			if (static::$current_page == $i)
			{
				$pagination .= '<li class="'.static::$template['active_class'].'">'.anchor(static::$url.'/'.$url, $i).'</li>';
			}
			else
			{
				$pagination .= '<li>'.anchor(static::$url.'/'.$url, $i).'</li>';
			}
		}

		return static::$template['page_start'].$pagination.static::$template['page_end'];
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Genera el query string para evitar duplicados o perdida de variables
	 *
	 *
	 * @param int $num Numero de la página
	 * @return string Query string reformado
	 */
	protected static function get_query_string($num = 0)
	{
        $query_string = array();
        
        $get = $_GET;
        
        if (_get('page'))
        {
            unset($get['page']);
        }
        
        foreach ($get as $key => $val)
        {
            $query_string[] = $key.'='.$val;
        }
        
        if ( ! isset($get['page']))
        {
            $query_string[] = 'page='.$num; 
        }
        
        return '?'.implode('&', $query_string);
	}

	// --------------------------------------------------------------------

	/**
	 * Link de 'Anterior' de la paginación
	 *
	 * @access public
	 * @param string $value Texto mostrado en el link
	 * @return mixed    El link de 'Siguiente'
	 */
	public static function next_link($value)
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		if (static::$current_page == static::$total_pages)
		{
		    $page = static::get_query_string(static::$current_page);
			return static::$template['item_start'].anchor(static::$url.'/'.$page, $value.static::$template['next_mark']).static::$template['item_end'];
		}
		else
		{
			$next_page = static::get_query_string(static::$current_page + 1);
			return static::$template['item_start'].anchor(static::$url.'/'.$next_page, $value.static::$template['next_mark']).static::$template['item_end'];
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Link de 'Anterior' de la paginación
	 *
	 * @access public
	 * @param string $value Texto mostrado en el link
	 * @return mixed    El link de 'Anterior'
	 */
	public static function prev_link($value)
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		if (static::$current_page == 1)
		{
		    $page = static::get_query_string(static::$current_page);
			return static::$template['item_start'].anchor(static::$url.'/'.$page,static::$template['previous_mark'].$value).static::$template['item_end'];
		}
		else
		{
			$previous_page = static::$current_page - 1;
			$previous_page = static::get_query_string(($previous_page == 1) ?: $previous_page);
			return static::$template['item_start'].anchor(static::$url.'/'.$previous_page, static::$template['previous_mark'].$value).static::$template['item_end'];
		}
	}
    
}