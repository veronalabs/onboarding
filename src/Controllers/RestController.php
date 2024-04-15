<?php 

namespace Veronalabs\Onboarding\Controllers;

class RestController
{

    public function stepHandler($step, $args)
    {
        return "blah blah";
    }

    public function __call($method, $args)
    {

        if( ! method_exists($this, $method) && strpos($method, "handle_step_") == 0)
        {   
            $stepName = substr($method, 12);
            return $this->stepHandler($stepName, $args);
        }

    }


}