<?php

namespace Application\Core;

class App_Model extends \Sense\Core\Model {

    /**
     * @var array $join contiene las relaciones básicas de la tabla
     */
    protected $join = array();

    /**
     * @var string $table contiene la tabla del modelo actual
     */
    protected $table = '';

    /**
     * @var string $find contiene el identificador del modelo actual
     */
    protected $id = 'id';

    /**
     * @var string $base_select contiene los campos basicos a seleccionar
     */    
    protected $select = '';

    
    protected $from = '';
    /**
     * @var string $where contiene los campos a filtrar en el where
     */
    protected $where = '';
    
    /**
     * @var string $order_by contiene los campos para definir el ordenamiento
     */
    protected $order_by = '';

    function __construct()
    {
        parent::__construct();
        
        if (empty($this->table))
        {
            $this->table = strtolower(str_ireplace('_Model', '', get_class($this)));
        }
    }

    function _before_save(){}
    function _after_save(){}
    /**
     * Realiza un query buscando todos los registros con la preparación 
     * básica realizada en el modelo, ej.:
     * 
     * SELECT * FROM table
     *
     * @return  array
     */
    function find_all()
    {
        $vars = func_get_args();
        $this->_set_filters(func_get_args());
                 
        $this->_setup();
         
        $results = $this->db->get($this->table);

        return $results->result();
    }
    
    private function _set_filters($params)
    {     
        if (isset($params[0]))
        {
            $this->db->or_where($params[0]);
        }
    
        if (isset($params[1]))
        {
            $this->db->order_by($params[1]);
        }      

        if (isset($params[2]) AND isset($params[3]))
        {
            $this->db->limit($params[3], $params[2]);
        }   
    }
    
    /**
     * Realiza un query buscando todos los registros con la preparación 
     * básica realizada en el modelo y recibiendo un id para filtrar por tal campo, 
     * ej.:
     * 
     * SELECT * FROM table WHERE table.id = '$id'
     *
     * @param   numeric el id a buscar
     * @return  array   el array con el resultado de la clase DB
     */
    function find($id = null)
    {
        //No hay nada, devuelva nada.
        if (is_null($id))
        {
            return null;
        }

        $this->_setup();

        $this->db->where("{$this->table}.{$this->id} = '$id'")
                 ->limit(1);

        $results = $this->db->get($this->table);
        
        $this->_setup_relationships($results);
        
        return $results;
    }
    
    function order($by = NULL, $type = '')
    {
        if (is_null($by))
        {
            return $this;
        }
        
        $this->db->order_by($by, $type);
        
        return $this;
    }
        
    function by($key = NULL, $value = NULL, $type = 'AND')
    {
        if (strtolower($type) == 'or')
        {
            $this->db->or_where($key, $value, $type);
        }
        elseif (strtolower($type) == 'in')
        {
            $this->db->where_in($key, $value, 'IN');
        }
        else 
        {
            $this->db->where($key, $value, 'AND');
        }
        
        return $this;
    }
    
    /**
     * Realiza un query buscando todos los registros con la preparación 
     * básica realizada en el modelo pero el resultado va a ser un total nada mas, 
     * ej.:
     * 
     * SELECT count(id) as total FROM table
     *
     * @return  string el numero total de filas
     */
    function count_all()
    {
        $this->_setup(true);
        
        $result = $this->db->get($this->table);
        
        if ($result->num_rows() > 0)
        {
            return $result->row()->total;
        }
        
        return 0; 
    }
    
    /**
     * Realiza un query insertando el array asociativo que llegue, verifica si existe el uid, si
     * existe actualiza el registro, sino lo saca del array para que no cree uno nuevo o genere un error
     *
     * @param   array el array con los datos a guardar
     * @return  bool  true o false dependiendo del resultado del query
     */
    function save($save, $table = '')
    {   
        $save = (method_exists($this, 'before_save') ? $this->before_save($save, $table) : $save);
    
        $field = array();
        
        if (isset($save[$this->id]))
        {
            $field = $this->find($save[$this->id]);            
        }
        
        $table = ( ! empty($table) ? $table : $this->table );
        
        if ( empty($field) OR $field->num_rows() == 0)
        {
            $return = $this->db->insert($save, $table);
        }
        else
        {
            $uid = $save[$this->id];
            
            unset($save[$this->id]);
            
            $field = $field->row();
            
            if ( ! isset($field->id))
            {
                $return = FALSE;
            }
            
            $return = $this->db->update($save, $table, "id = {$field->id}");
        }
        
        $return = (method_exists($this, 'after_save') ? $this->before_save($save, $table, $return) : $return);
        
        return $return;
    }
    
    function update_by($save, $fields = '', $value = '')
    {
        if (empty($fields))
        {
            return FALSE;
        }
        
        $save = (method_exists($this, 'before_save') ? $this->before_save($save, $table) : $save);
        
        if ( ! is_array($fields))
        {
            $fields = array($fields => $value);
        }
        
        $this->db->where($fields);
        
        $return = $this->db->update($save, $this->table);   

        $return = (method_exists($this, 'after_save') ? $this->before_save($save, $this->table, $return) : $return);
        
        return $return;
    }
    
    /**
     * Prepara las partes del query que llegan desde el modelo tales como las asociaciones, 
     * selects, where.
     *
     * @param   bool si se hace un query de conteo o no
     * @return  void
     */ 
    private function _setup($count = FALSE)
    {   
        if ( ! empty($this->from))
        {            
            $this->db->from($this->from);
        }
        
        if ( ! empty($this->select))
        {
            $this->base_select = $this->select;
        }
        
        if ($this->db->is_empty_select())
        {
            if ($count === FALSE)
            {
                $this->base_select = (empty($this->base_select) ? "{$this->table}.*": $this->base_select);
                $this->db->select($this->base_select);            
            }
            else
            {
                $this->db->select("count({$this->table}.{$this->id}) as total");
            }
        }
        $this->db->join($this->join);
        $this->db->where($this->where);
                
        if ( ! empty($this->order_by) AND $this->db->is_empty_order_by())
        {
            $this->db->order_by($this->order_by);
        }
    }
    
     
    /**
     * Realiza el query dinámico llamado desde la función mágica.
     *
     * @param   bool si se hace un query de conteo o no
     * @return  void
     */ 
    function find_by($field = NULL, $value = NULL, $order = NULL, $limit = NULL, $offset = NULL)
    {        
        $this->_setup();          
        
        if ( ! is_array($field) AND is_array($value))
        {
            $this->db->where_in($field, $value);
        }
        else 
        {
            $this->db->where($field, $value);      
        }
        
        if ( ! is_null($order))
        {
            $this->db->order_by($order);
        }      

        if ( ! is_null($limit) AND ! is_null($offset))
        {
            $this->db->limit($limit, $offset);
        }   
        
        $results = $this->db->get($this->table);
        
        if ( ! empty($this->has_many))
        {
            $results = $this->_has_many($results);
        }
        
        return $results;         
    }
    
    function count_by($field = NULL, $value = NULL)
    {        
        $this->_setup(TRUE);          
        
        if ( ! is_array($field) AND is_array($value))
        {
            $this->db->where_in($field, $value);
        }
        else 
        {
            $this->db->where($field, $value);      
        }
        
        $result = $this->db->get($this->table);  

        if ($result->num_rows() > 0)
        {
            return $result->row()->total;
        }
        
        return 0;
    }
    
}