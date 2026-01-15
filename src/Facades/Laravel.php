<?php

namespace HardImpact\Craft\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \HardImpact\Craft\Laravel
 */
class Laravel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \HardImpact\Craft\Laravel::class;
    }
}
