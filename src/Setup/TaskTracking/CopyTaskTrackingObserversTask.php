<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyTaskTrackingObserversTask extends Task
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
        // Copy observers
        $observersStubPath = __DIR__.'/../../../resources/stubs/task-tracking/app/Observers';
        $observersDestinationPath = app_path('Observers');

        if (! $this->copyDirectory($observersStubPath, $observersDestinationPath)) {
            $this->error('Failed to copy task tracking observers.');

            return false;
        }

        $this->info('Task tracking observers copied successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying task tracking observers';
    }
}
