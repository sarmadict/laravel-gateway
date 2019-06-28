<?php

namespace Sarmad\Gateway\Contracts;

interface Factory
{
    /**
     * Get an Gateway provider implementation.
     *
     * @param  string $driver
     * @return \Sarmad\Gateway\Contracts\Provider
     */
    public function driver($driver = null);
}
