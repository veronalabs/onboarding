<?php 

namespace Veronalabs\Onboarding;
use VeronaLabs\Onboarding\Contracts\OnboardingContract;
use Veronalabs\Onboarding\Contracts\ResourceContract;
use VeronaLabs\Onboarding\Contracts\RouteContract;
use Veronalabs\Onboarding\Contracts\SourceContract;
use Veronalabs\Onboarding\Routes\Route;

class Onboarding implements OnboardingContract
{

    private $config = [];
    private $steps = [];

    private bool $isRegistered = false;

    public SourceContract $resource;

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
        $this->router->register();
        
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
            $route->methods = $step['methods'] == null ? "POST" : $step['methods'];
            $route->args = $step['args'];
            $route->callback = function() use ($step, $slug){
                do_action('before_handle_step_' . $slug);
                call_user_func($step['callback']);
                do_action('after_handle_step_' . $slug);
            };

            $this->router->setRoute($route);
        }
    }

}