<?php 
namespace Veronalabs\Onboarding\Contracts;

interface ResourceContract extends SourceContract
{

    public function load();

    public function save();

}