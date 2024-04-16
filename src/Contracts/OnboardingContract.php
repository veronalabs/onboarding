<?php 
namespace Veronalabs\Onboarding\Contracts;

interface OnboardingContract extends HookableContract
{

    public function config(array $configs);

    public function steps(array $steps);

}