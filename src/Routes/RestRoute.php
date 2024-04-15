<?php

namespace Veronalabs\Onboarding\Routes;

use Veronalabs\Onboarding\Contracts\RouteContract;
use Veronalabs\Onboarding\Source\SourceAbstract;

class RestRoute extends SourceAbstract implements RouteContract
{

    public function validation($key)
    {
        return true;
    }

    public function setRoute(Route $route)
    {
        $this->data[] = $route;
        return $this;
    }

    public function register()
    {
        add_action('rest_api_init', function () {
            foreach ($this->data as $route) {
                register_rest_route($route->namespace, $route->slug, array(
                    'methods' => $route->methods,
                    'callback' => $route->callback,
                    'args' => $route->args,
                ));
            }
        });
    }
}
