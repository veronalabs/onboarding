<?php 

namespace Veronalabs\Onboarding\Contracts;

use Veronalabs\Onboarding\Routes\Route;

interface RouteContract extends HookableContract
{

    public function setRoute(Route $route);

}