<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyTaskTrackingMigrationsTask extends Task
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
        // Copy migrations
        $migrationsStubPath = __DIR__.'/../../../resources/stubs/task-tracking/database/migrations';
        $migrationsDestinationPath = database_path('migrations');

        if (! $this->copyDirectory($migrationsStubPath, $migrationsDestinationPath)) {
            $this->error('Failed to copy task tracking migrations.');

            return false;
        }

        $this->info('Task tracking migrations copied successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying task tracking migrations';
    }
}
