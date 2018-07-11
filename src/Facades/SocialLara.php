<?php

namespace nattaponra\sociallara\Facades;

use Illuminate\Support\Facades\Facade;

class SocialLara extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sociallara';
    }
}