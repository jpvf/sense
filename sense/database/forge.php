<?php

namespace Sense\Database;

class Forge {

	private static $instance;
	public $fields = array();
	public $primary_keys = array();
	public $keys = array();

	public static function getInstance()
	{
		if ( ! self::$instance)
		{
			self::$instance = new Forge;
		}

		return self::$instance;
	}
	
	function add_field($field = '')
	{
		if ($field == '')
		{
			return;
		}

		if (is_string($field))
		{
			if ($field == 'id')
			{
				$this->add_field(array(
					'id' => array(
								'type' => 'INT',
								'constraint' => 11,
								'auto_increment' => TRUE
								)
				));

				$this->add_key('id', TRUE);
			}
			else
			{
				if (strpos($field, ' ') === FALSE)
				{
					return;
				}

				$this->fields[] = $field;
			}
		}

		if (is_array($field))
		{
			$this->fields = array_merge($this->fields, $field);
		}

		return $this;
	}

	function add_key($key = '', $primary = FALSE)
	{
		if (is_array($key))
		{
			foreach($key as $one)
			{
				$this->add_key($one, $primary);
			}

			return;
		}

		if ($key == '')
		{
			return;
		}

		if ($primary === TRUE)
		{
			$this->primary_keys[] = $key;
		}
		else
		{
			$this->keys[] = $key;
		}
	}

	function _process_fields()
	{
		$current_field_count = 0;
		$sql = '';

		foreach ($this->fields as $field => $attributes)
		{
			if (is_numeric($field))
			{
				$attributes = explode(' ', $attributes, 2);
				$attributes = ' `'.$attributes[0].'` '.$attributes[1];
				$sql .= "\n\t$attributes";
			}
			else
			{
				$attributes = array_change_key_case($attributes, CASE_UPPER);

				$sql .= "\n\t `".$field.'`';

				if (array_key_exists('NAME', $attributes))
				{
					$sql .= ' '.$attributes['NAME'].' ';
				}

				if (array_key_exists('TYPE', $attributes))
				{
					$sql .=  ' '.$attributes['TYPE'];

					if (array_key_exists('CONSTRAINT', $attributes))
					{
						switch ($attributes['TYPE'])
						{
							case 'decimal':
							case 'float':
							case 'numeric':
								$sql .= '('.implode(',', $attributes['CONSTRAINT']).')';
							break;

							case 'enum':
							case 'set':
								$sql .= '("'.implode('","', $attributes['CONSTRAINT']).'")';
							break;

							default:
								$sql .= '('.$attributes['CONSTRAINT'].')';
						}
					}
				}

				if (array_key_exists('UNSIGNED', $attributes) && $attributes['UNSIGNED'] === TRUE)
				{
					$sql .= ' UNSIGNED';
				}

				if (array_key_exists('DEFAULT', $attributes))
				{
					$sql .= ' DEFAULT \''.$attributes['DEFAULT'].'\'';
				}

				if (array_key_exists('NULL', $attributes))
				{
					$sql .= ($attributes['NULL'] === TRUE) ? ' NULL' : ' NOT NULL';
				}

				if (array_key_exists('AUTO_INCREMENT', $attributes) && $attributes['AUTO_INCREMENT'] === TRUE)
				{
					$sql .= ' AUTO_INCREMENT';
				}
			}

			// don't add a comma on the end of the last field
			if (++$current_field_count < count($this->fields))
			{
				$sql .= ',';
			}
		}

		return $sql;
	}

	function create_table($table = '', $if_not_exists = FALSE, $create_table = TRUE)
	{
		if ($table == '' OR count($this->fields) == 0)
		{
			return;
		}

		$fields = $this->fields;
		$primary_keys = $this->primary_keys;
		$keys = $this->keys;

		$sql = 'CREATE TABLE ';

		if ($if_not_exists === TRUE)
		{
			$sql .= 'IF NOT EXISTS ';
		}

		$sql .= '`'.$table."` (";

		$sql .= $this->_process_fields($fields);

		if (count($primary_keys) > 0)
		{
			$key_name = implode('_', $primary_keys);
			$primary_keys = $primary_keys;
			$sql .= ",\n\tPRIMARY KEY `".$key_name."` (" . implode(', ', $primary_keys) . ")";
		}

		if (is_array($keys) && count($keys) > 0)
		{
			foreach ($keys as $key)
			{
				if (is_array($key))
				{
					$key_name = implode('_', $key);
					$key = $key;
				}
				else
				{
					$key_name = $key;
					$key = array($key_name);
				}

				$sql .= ",\n\tKEY `{$key_name}` (" . implode(', ', $key) . ")";
			}
		}

		$sql .= "\n) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_general_ci`;";

		$this->_reset();

		if ($create_table === TRUE)
		{
			return db::getInstance()->query($sql);
		}
		return $sql;
	}

	function drop_table($table = '', $drop_table = TRUE)
	{
		if (empty($table))
		{
			return FALSE;
		}

		$sql = 'DROP TABLE '.$table;

		if ($drop_table === TRUE)
		{
			return db::getInstance()->query($sql);
		}
		return $sql; 
	}

	private function _reset()
	{
		$this->fields		= array();
		$this->keys			= array();
		$this->primary_keys	= array();
	}


}