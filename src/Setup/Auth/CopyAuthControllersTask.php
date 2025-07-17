<?php

namespace HardImpact\Liftoff\Setup\Auth;

use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyAuthControllersTask extends Task
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
        $stubPath = __DIR__.'/../../../resources/stubs/auth/app/Http/Controllers/Auth';
        $destinationPath = app_path('Http/Controllers/Auth');

        $replacements = [
            '{{namespace}}' => app()->getNamespace(),
            '{{userModel}}' => config('auth.providers.users.model', 'App\\Models\\User'),
        ];

        if ($this->copyDirectory($stubPath, $destinationPath, $replacements)) {
            $this->info('Authentication controllers copied successfully.');

            return true;
        }

        $this->error('Failed to copy authentication controllers.');

        return false;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying authentication controllers';
    }
}
