<?php 

namespace Veronalabs\Onboarding\Resources;

use Veronalabs\Onboarding\Contracts\ResourceContract;
use Veronalabs\Onboarding\Source\SourceAbstract;

class Option extends SourceAbstract implements ResourceContract
{

    public function __construct($key, $default = [])
    {

        $this->key = $key;
        $this->data = [];

    }

    public function load($force = false)
    {
        if( $force === true || $this->data === [] )
        {
            $data = get_option($this->key, true);
            $this->data = $data ? $data : [];
        }else{
            return $this->data;
        }
    }

    public function validation($key)
    {
        return true;
    }

    public function save()
    {
        return update_option($this->key, $this->data);
    }


}