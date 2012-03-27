<?php 

class Upload{
    
    private static $upload;
    public $file = null;
    public $file_exists = false;
    
    public function __construct($field = '')
    {
        if (isset($_FILES[$field]) AND ! empty($_FILES[$field]) AND ! empty($field) AND $field != '')
        {
            $this->file = $_FILES[$field];
            $this->file_exists = true;
            return $this;
        }

        return false;
    }

    private function _check_ext()
    {
        $this->allowed_ext          = explode('|', $this->allowed);
        $this->file_ext             = strtolower(strrchr($this->file['name'],'.'));
        list($dot, $this->file_ext) = explode('.', $this->file_ext);
        $extension_result = false;

        foreach ($this->allowed_ext as $ext)
        {
            if ($ext == $this->file_ext)
            { 
                $extension_result = true;
                break;
            }
        }
        return $extension_result;          
    }
    
    private function _check_size()
    {
        $this->file_size = $this->file['size'];
        
        if ($this->max_size >= $this->file['size'])
        {
            return true;
        }
        
        return false;
    }
    
    private function _move()
    {
        $this->file_ext = ($this->file_ext == 'jpg') ? ('jpeg') : ($this->file_ext);
        $this->base_name = $this->file['name'];
        
        if (isset($this->file_name) AND ! empty($this->file_name))
        {
            $this->file['name'] = $this->file_name . '.' .$this->file_ext;
        }
        
        if (isset($this->timestamp) AND ! empty($this->timestamp) AND $this->timestamp === true)
        {
            $ext  = strrchr($this->file['name'],'.');
            $name = reverse_strrchr($this->file['name'],'.');
            
            list($dot, $ext) = explode('.', $ext);
            $ext = ($ext == 'jpg') ? ('jpeg') : ($ext);

            $this->timestamp_val = date('YmdHis');
            $this->file['name'] = $name . '-' . $this->timestamp_val . '.' . $ext;
        }
         
        if (move_uploaded_file($this->file['tmp_name'], $this->upload_path .  $this->file['name'] )) 
        {
            return true;
        }
        
        return false;
    }
    
    public function do_upload($config = array())
    {
        if ( ! $this->file)
        {
            return;
        }    
        
        $this->_setDefaults($config);
        
        if ( ! $this->_check_ext())
        {
            $this->error = 'Only JPG and JPEG images can be uploaded.';
            return false;
        }
        
        if ( ! $this->_check_size())
        {
            $this->error = 'Filesize exceeded the maximum.';
            return false;
        }
        
        if ( file_exists($this->upload_path . $this->file['name']))
        {
            return false;
        }
        
        if ( ! $this->_move())
        {
            return false;
        }
        
        return true;
        
    }
    
    private function _setDefaults($upload_config = array())
    {
        if (count($upload_config) > 0)
        {
            foreach ($upload_config as $key => $val)
            {
                $this->$key = $val;
            }
        }
    }
    
    public function get_name()
    {
        if ($this->file_exists === false)
        {
            return false;
        }
        return $this->upload_path . $this->file['name'];
    }
    
    public function get_size()
    {
        return $this->file_size;
    }
    
    function get_base_filename()
    {
        return $this->base_name;
    }
    
    public function get_timestamp()
    {
        return $this->timestamp_val;
    }

}