<?php

namespace Sarmad\Gateway\Facades;

use Illuminate\Support\Facades\Facade;
use Sarmad\Gateway\Contracts\Factory;

/**
 * @see \Sarmad\Gateway\GatewayManager
 */
class Gateway extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
