<?php

namespace HardImpact\Craft;

use HardImpact\Craft\Commands\SetupCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel')
            ->hasConfigFile()
            ->hasMigration('create_users_table')
            ->hasCommand(SetupCommand::class);
    }
}
