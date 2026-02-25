<?php

namespace HardImpact\Craft;

use Carbon\CarbonImmutable;
use HardImpact\Craft\Commands\SetupCommand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Sleep;
use Illuminate\Validation\Rules\Password;
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

    public function packageBooted(): void
    {
        if (config('laravel.defaults.strict_models')) {
            Model::shouldBeStrict();
        }

        if (config('laravel.defaults.auto_eager_load') && method_exists(Model::class, 'automaticallyEagerLoadRelationships')) {
            Model::automaticallyEagerLoadRelationships();
        }

        if (config('laravel.defaults.immutable_dates')) {
            Date::use(CarbonImmutable::class);
        }

        if (config('laravel.defaults.force_https') && $this->app->isProduction()) {
            URL::forceHttps();
        }

        if (config('laravel.defaults.prohibit_destructive_commands') && $this->app->isProduction()) {
            DB::prohibitDestructiveCommands();
        }

        if (config('laravel.defaults.aggressive_prefetching')) {
            $this->app->make(Vite::class)->useAggressivePrefetching();
        }

        if (config('laravel.defaults.prevent_stray_requests') && $this->app->runningUnitTests()) {
            Http::preventStrayRequests();
        }

        if (config('laravel.defaults.fake_sleep') && $this->app->runningUnitTests()) {
            Sleep::fake();
        }

        if (config('laravel.defaults.default_password_rules') && $this->app->isProduction()) {
            Password::defaults(fn () => Password::min(12)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised());
        }
    }
}
