<?php 

class Acl{

    private static $acl;
    
    public static function getInstance()
    {
    	if ( ! self::$acl) 
        { 
            self::$acl = new acl(); 
        } 
        return self::$acl; 
    }
    
    function __construct()
    {
        self::$acl = $this;
    }
    
    function set_data($data = array())
    {
        if ( ! empty($data))
        {
            foreach ($data as $key => $val)
            {
                $this->$key = $val;
            }
        } 
        return $this;
    }

    function is_allowed($resource = NULL, $id_user = NULL,$id = 0)
    {
        if (is_null($resource))
        {
            return FALSE;
        }
        
        if (is_null($id_user))
        {
            $id_user = $this->id;
        }

        $resource = $this->find_resource($resource);
        $results  = $this->find_permission($resource, $id_user, $id);

        if ($results->num_rows() > 0)
        {
            $permission = $results->row();

            return $permission->value == 1 ? TRUE : FALSE;
        }                            

        return FALSE;
    }

    function allow($resource = NULL, $id_user = NULL, $id_project = 0)
    {
        $this->save_permission($resource, $id_user, $id_project, 1);
        return $this;
    }

    function deny($resource = NULL, $id_user = NULL, $id_project = 0)
    {
        $this->save_permission($resource, $id_user, $id_project, 0);
        return $this;
    }

    function save_permission($resource = NULL, $id_user = NULL, $id_project = 0, $value)
    {
        if (is_null($resource) OR is_null($id_user))
        {
            return FALSE;
        }

        if (is_string($resource))
        {
            $resource = $this->find_resource($resource);          
        }

        if ( ! is_numeric($resource) OR $resource < 1)
        {
            return FALSE;
        }

        $permission = $this->find_permission($resource, $id_user, $id_project);

        $save = array();

        if ($permission->num_rows() > 0)
        {
            $save['value'] = $value;

            $where = "id_resource = $resource AND id_project = $id_project AND id_user = $id_user";
            return db::getInstance()->update($save, 'pmt_users_permissions', $where);
        }

        $save['id_resource'] = $resource;
        $save['id_user']     = $id_user;
        $save['id_project']  = $id_project;
        $save['value']       = $value;

        return db::getInstance()->insert($save, 'pmt_users_permissions');
    }

    function in_group($group = NULL, $user = NULL)
    {
        if ( ! is_null($group) OR ! is_null($user))
        {
            return FALSE;
        }

        $group = $this->find_group_by_name($group);

        if ($group->num_rows() > 0)
        {
            $id = $group->row()->id;

            $user = db::getInstance()->where("pmt_users.id = $user")
                                     ->from('pmt_users')
                                     ->join('pmt_groups');
        }

        return FALSE;
    }

    function find_permission($resource, $user, $id)
    {
        return db::getInstance()->where("id_resource = $resource AND id_project = $id AND id_user = $user")
                                ->get('pmt_users_permissions');
    }


    function find_resource($resource)
    {
        return db::getInstance()->where("name = '$resource'")
                                ->get('pmt_users_resources')
                                ->row()
                                ->id;
    }

    function get_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    function find_parent_resources()
    {
        return db::getInstance()->where('parent_id = 0')
                                ->get('pmt_users_resources')
                                ->result();      
    }

    function find_resources($id = NULL)
    {
        if (is_null($id))
        {
          return FALSE;
        }

        return db::getInstance()->where("parent_id = $id")
                                ->get('pmt_users_resources')
                                ->result();
    }

    function find_group($id)
    {
        return db::getInstance()->where("pmt_users_groups.id = $id")
                                ->get('pmt_users_groups')
                                ->result(); 
    }

    function find_group_by_name($name)
    {
        return db::getInstance()->where("pmt_users_groups.name = '$name'")
                                ->get('pmt_users_groups')
                                ->result(); 
    }

    function find_all_groups()
    {
        return db::getInstance()->get('pmt_users_groups')
                                ->result();
    }

    function find_groups_by_type($internal = 0)
    {
        return db::getInstance()->where("pmt_users_groups.internal = $internal")
                                ->get('pmt_users_groups')
                                ->result();  
    }

    function is_owner()
    {
        $owner = db::getInstance()->where("pmt_users.id = {$this->id} AND pmt_companies.owner = 1")
                                  ->join('pmt_users', 'pmt_users.id_company = pmt_companies.id')
                                  ->get('pmt_companies');

        return $owner->num_rows() == 1 ? TRUE : FALSE;
    }
}