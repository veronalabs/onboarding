<?php 
namespace Veronalabs\Onboarding\Contracts;

interface OnboardingContract extends HookableContract
{

    public function router(RouteContract $router);

    public function config(ResourceContract $configs);

    public function steps(ResourceContract $steps);

}