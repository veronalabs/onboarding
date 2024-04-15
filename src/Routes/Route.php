<?php 

namespace Veronalabs\Onboarding\Routes;
use Veronalabs\Onboarding\Source\SourceAbstract;

class Route extends SourceAbstract
{

    public function __construct()
    {
        $this->allowed = [
            "namespace",
            "slug",
            "methods",
            "callback",
            "args"
        ];
    }

    public function validation($key)
    {
        if(array_key_exists($key, $this->allowed)) return true;
        return false;
    }

}