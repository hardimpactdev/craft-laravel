<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyTaskTrackingModelsTask extends Task
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
        // Copy models
        $modelsStubPath = __DIR__.'/../../../resources/stubs/task-tracking/app/Models';
        $modelsDestinationPath = app_path('Models');

        if (! $this->copyDirectory($modelsStubPath, $modelsDestinationPath)) {
            $this->error('Failed to copy task tracking models.');

            return false;
        }

        $this->info('Task tracking models copied successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying task tracking models';
    }
}
