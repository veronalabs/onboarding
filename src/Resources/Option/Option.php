<?php 

namespace Veronalabs\Onboarding\Source\Option;

use Veronalabs\Onboarding\Contracts\ResourceContract;
use Veronalabs\Onboarding\Source\SourceAbstract;

class Option extends SourceAbstract implements ResourceContract
{

    public function __construct($key)
    {

        $this->key = $key;

        $this->allowed = [

        ];
    }

    public function load()
    {
        return get_option($this->key, true);
    }

    public function validation($key)
    {
        return array_key_exists($key, $this->allowed);
    }

    public function save()
    {
        return update_option($this->key, $this->data);
    }


}