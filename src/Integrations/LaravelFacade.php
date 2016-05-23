<?php

namespace MenaraSolutions\Geographer\Integrations;

use Illuminate\Support\Facades\Facade;

/**
 * Class LaravelFacade
 * @package Dusterio\LinkPreview\Integrations
 * @codeCoverageIgnore
 */
class LaravelFacade extends Facade
{
    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geographer';
    }
}
