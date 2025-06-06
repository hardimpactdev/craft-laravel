<?php

namespace Livtoff\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Livtoff\Laravel\Laravel
 */
class Laravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Livtoff\Laravel\Laravel::class;
    }
}
