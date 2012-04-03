<?php

class Profile_Controller extends Application\Core\App_Controller {

	function index()
	{
		SenseReporter::register('users');

		SenseReporter::config('users', function($config)
		{
			$users_model = Model::factory('account/account_model');


			$config->set_table_classes('table table-bordered table-striped');
			$config->set_table_header_tag('th');

			$config->table(function($table)
			{
				$table->column('id', 'User Id');
				$table->column('username', 'Username');
				$table->column('email', array(
					'label' => 'E-mail'
				));
				$table->column('created_at', array(
					'as' => function($row)
					{
						return date('d \d\e\ M \d\e Y', strtotime($row->created_at)); 
					},
					'label' => 'Created At'
				));

				$table->column('active', array(
					'as' => function($row)
					{
						return $row->active ? 'Si' : 'No'; 
					},
					'label' => 'Active'
				));

				$table->column('invento', array(
					'as' => function ($row) 
					{
						return rand() * 5;
					},
					'label' => '<a href="#" title="¿Que es esto?">Columna inventada</a>'
				));

				$table->column('monto', array(
					'as' => function ($row) 
					{
						return formato_monto(10000);
					},
					'label' => '<a href="#" title="¿Que es esto?">Monto</a>'
				));
			});

			$config->results($users_model->find_all());
		});


		echo '
<!doctype html>
<html>
<head>
	<title>Tables</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/css/bootstrap.css">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="span12">
		'.SenseReporter::render_table('users').'
			</div>
		</div>
	</div>
</body>
</html>';
		
	}	

}


class SenseReporter {

	private static $_resources = array();

	private $_table_classes    = 'table table-bordered';
	private $_table_header_tag = 'th';
	private $_resource 		   = null;
	private $_results		   = null;

	public function __construct($resource)
	{
		$this->_resource = $resource;
		$this->senseTable = new senseTable;
	}

	public static function register($resource = '')
	{
		static::$_resources[$resource] = new SenseReporter($resource);
	}

	public static function config($resource, $callback)
	{
		call_user_func($callback, static::$_resources[$resource]);
	}

	public function table($callback = null)
	{
		return call_user_func($callback, $this->senseTable);
	}

	public function results($results = array())
	{
		$this->_results = $results;
		return $this;
	}

	public static function render_table($resource)
	{
		$r = static::$_resources[$resource];

		$table = '<table class="'.$r->_table_classes.'"><thead><tr>';

		foreach ($r->senseTable->get_columns() as $column => $attrs)
		{
			if (is_string($attrs))
			{
				$column = $attrs;				
			}
			elseif (is_array($attrs) AND isset($attrs['label']))
			{
				$column = $attrs['label'];
			}

			$table .= '<'.$r->_table_header_tag.'>'.$column.'</'.$r->_table_header_tag.'>';
		}

		$table .= '</tr></thead>';

		$rows = $r->_results;

		foreach ($rows as $row)
		{
			$table .= '<tr>';

			foreach ($r->senseTable->get_columns() as $field => $attrs)
			{
				if ( ! empty($attrs))
				{
					if (isset($attrs['as']) AND $attrs['as'] instanceof Closure)
					{
						$row->$field = call_user_func($attrs['as'], $row);
					}
				}
				$table .= '<td>'.$row->$field.'</td>';
			}

			$table .= '</tr>';
		}	

		$table .= '</table>';

		return $table;
	}


	public function set_table_classes($classes = '')
	{
		$this->_table_classes = $classes;
		return $this;
	}

	public function set_table_header_tag($tag = 'th')
	{
		$this->_table_header_tag = $tag;
		return $this;
	}

}

class SenseTable {

	private $_columns = array();


	function __construct(){}

	function column($column = '', $attrs = array())
	{
		$this->_columns[$column] = $attrs;
	}

	function get_columns()
	{
		return $this->_columns;
	}

}