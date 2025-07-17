<?php

namespace HardImpact\Liftoff\Setup\Auth;

use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyAuthViewsTask extends Task
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
        // Copy auth pages
        $pagesStubPath = __DIR__.'/../../../resources/stubs/auth/resources/js/pages/auth';
        $pagesDestinationPath = resource_path('js/pages/auth');

        if (! $this->copyDirectory($pagesStubPath, $pagesDestinationPath)) {
            $this->error('Failed to copy authentication pages.');

            return false;
        }

        $this->info('Authentication pages copied successfully.');

        // Copy auth layout
        $layoutStubPath = __DIR__.'/../../../resources/stubs/auth/resources/js/layouts/auth';
        $layoutDestinationPath = resource_path('js/layouts/auth');

        if (! $this->copyDirectory($layoutStubPath, $layoutDestinationPath)) {
            $this->error('Failed to copy authentication layout.');

            return false;
        }

        $this->info('Authentication layout copied successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying authentication views and layouts';
    }
}
