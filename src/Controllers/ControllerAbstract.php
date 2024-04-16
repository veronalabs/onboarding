<?php 

namespace Veronalabs\Onboarding\Controllers;

use WP_REST_Request;

abstract class ControllerAbstract
{

    public function validation($fields, $data)
    {

        foreach($fields as $field)
        {
            
        }

    }

    public function __call($method, $args)
    {

        if( ! method_exists($this, $method) && strpos($method, "handle_step_") == 0)
        {   
            $stepName = substr($method, 12);
            return $this->stepHandler($stepName, $args[0], $args[1]);
        }

    }

}