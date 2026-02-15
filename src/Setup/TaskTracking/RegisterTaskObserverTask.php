<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class RegisterTaskObserverTask extends Task
{
    /**
     * Create a new task instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    /**
     * Run the task.
     */
    public function run(): bool
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');

        if (! $this->filesystem->exists($providerPath)) {
            $this->error('AppServiceProvider not found.');

            return false;
        }

        $content = $this->filesystem->get($providerPath);

        // Check if already registered
        if (str_contains($content, 'TaskObserver')) {
            $this->info('TaskObserver already registered.');

            return true;
        }

        // Add import
        if (! str_contains($content, 'use App\\Models\\Task;')) {
            $content = str_replace(
                'namespace App\\Providers;',
                "namespace App\\Providers;\n\nuse App\\Models\\Task;\nuse App\\Observers\\TaskObserver;",
                $content
            );
        } elseif (! str_contains($content, 'use App\\Observers\\TaskObserver;')) {
            $content = str_replace(
                'use App\\Models\\Task;',
                "use App\\Models\\Task;\nuse App\\Observers\\TaskObserver;",
                $content
            );
        }

        // Add observer registration in boot method
        if (str_contains($content, 'public function boot()')) {
            // Boot method exists, add observer registration
            $content = preg_replace(
                '/(public function boot\([^)]*\):\s*void\s*\{)/',
                "$1\n        Task::observe(TaskObserver::class);",
                $content
            );
        } else {
            // Add boot method
            $content = preg_replace(
                '/(class AppServiceProvider extends ServiceProvider\s*\{)/',
                "$1\n    /**\n     * Bootstrap any application services.\n     */\n    public function boot(): void\n    {\n        Task::observe(TaskObserver::class);\n    }",
                $content
            );
        }

        if ($this->filesystem->put($providerPath, $content) === false) {
            $this->error('Failed to update AppServiceProvider.');

            return false;
        }

        $this->info('TaskObserver registered in AppServiceProvider.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Registering TaskObserver in AppServiceProvider';
    }
}
