<?php 

namespace Veronalabs\Onboarding\Controllers;

use Veronalabs\Onboarding\Contracts\ResourceContract;

class RestController extends ControllerAbstract
{
    
    private ResourceContract $resource;
    
    public function __construct(ResourceContract $resource)
    {
        $this->resource = $resource;
    }

    public function stepHandler($step, $request, $fields)
    {

        $this->validation($fields, $request->get_params());        
        $this->resource->load();
        
    }


}