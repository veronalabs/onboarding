<?php 

namespace Veronalabs\Onboarding;

use Reflection;
use ReflectionClass;
use Veronalabs\Onboarding\Contracts\OnboardingContract;
use Veronalabs\Onboarding\Contracts\ResourceContract;
use VeronaLabs\Onboarding\Contracts\RouteContract;
use Veronalabs\Onboarding\Contracts\SourceContract;
use Veronalabs\Onboarding\Controllers\RestController;
use Veronalabs\Onboarding\Resources\Option;
use Veronalabs\Onboarding\Routes\Route;
use WP_REST_Request;

class Onboarding implements OnboardingContract
{

    private $config = [];
    private $steps = [];

    public ResourceContract $resource;

    public RouteContract $router;
    
    public function router(RouteContract $router)
    {
        $this->router = $router;
        return $this;
    }

    public function config(ResourceContract $config)
    {
        $this->config = $config;
        return $this;
    }

    public function steps(ResourceContract $steps)
    {
        $this->steps = $steps;
        return $this;
    }

    public function register()
    {
        $this->config->save();
        
        
        $this->steps->save();
        
        $this->makeRouteFromSteps();
        $this->router->register();
    }

    private function makeRouteFromSteps()
    {
        
        foreach( $this->steps->load() as $slug => $step )
        {
            $route = new Route();
            $route->namespace  = $this->config->default_namespace == null ? "veronalabs/onboarding" : $this->config->default_namespace;
            $route->slug = $slug;
            $route->methods = $step['methods'] == null ? "GET" : $step['methods'];
            $route->args = $step['args'] ==  null ? [] : $step['args'];
            $route->callback = function( WP_REST_Request $request ) use ($step, $slug){
                do_action('before_handle_step_' . $slug);
                if( array_key_exists("callback", $step) )
                {
                    if(is_array($step['callback']))
                    {
                        $class = new $step['callback'][0];
                        $class->{$step['callback'][1]}($request, $step);
                    }else{
                        call_user_func($step['callback'], $request, $step);
                    }
                }else
                {
                    $method = "handle_step_".$slug;
                    $resource = get_class($this->config);
                    $controller = new RestController(new $resource(
                        isset($this->config->data_option_key) ? $this->config->data_option_key : "veronalabs_onboarding_data"  
                    ));
                    $controller->{$method}($request, $step['fields']);
                }
                do_action('after_handle_step_' . $slug);
            };
            
            $this->router->setRoute($route);
        }
    }

}