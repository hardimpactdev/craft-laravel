<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyTaskTrackingEventsTask extends Task
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
        // Copy events
        $eventsStubPath = __DIR__.'/../../../resources/stubs/task-tracking/app/Events';
        $eventsDestinationPath = app_path('Events');

        if (! $this->copyDirectory($eventsStubPath, $eventsDestinationPath)) {
            $this->error('Failed to copy task tracking events.');

            return false;
        }

        $this->info('Task tracking events copied successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying task tracking events';
    }
}
