<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyTaskTrackingListenersTask extends Task
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
        // Copy listeners
        $listenersStubPath = __DIR__.'/../../../resources/stubs/task-tracking/app/Listeners';
        $listenersDestinationPath = app_path('Listeners');

        if (! $this->copyDirectory($listenersStubPath, $listenersDestinationPath)) {
            $this->error('Failed to copy task tracking listeners.');

            return false;
        }

        $this->info('Task tracking listeners copied successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying task tracking listeners';
    }
}
