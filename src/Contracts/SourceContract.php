<?php 

namespace Veronalabs\Onboarding\Contracts;

interface SourceContract
{
    
    public function __set($key, $value);

    public function __get($key);

}