<?php 

namespace Veronalabs\Onboarding\Source;

abstract class SourceAbstract 
{

    protected $key;
    
    protected $data = [];

    protected $allowed = [];
    
    public abstract function validation($key);

    public function reset()
    {
        $this->data = [];
        return $this;
    }

    public function data()
    {
        return $this->data;
    }
    
    public function __get($key)
    {
        if(!property_exists($this, $key))
        {
            if(array_key_exists($key, $this->data))
            {
                return $this->data[$key];
            }else
            {
                return null;
            }
        }
    }

    public function __set($key, $value)
    {
        if(!property_exists($this, $key) && $this->validation($key)){
            return $this->data[$key] = $value;
        }   
    }

}