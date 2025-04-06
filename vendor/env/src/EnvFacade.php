<?php

namespace Env\Env;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Env\Env\Skeleton\SkeletonClass
 */
class EnvFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'env';
    }
}
