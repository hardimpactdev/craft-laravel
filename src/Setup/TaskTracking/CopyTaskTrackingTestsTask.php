<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class CopyTaskTrackingTestsTask extends Task
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
        // Copy tests
        $testsStubPath = __DIR__.'/../../../resources/stubs/task-tracking/tests/Feature';
        $testsDestinationPath = base_path('tests/Feature');

        if (! $this->copyDirectory($testsStubPath, $testsDestinationPath)) {
            $this->error('Failed to copy task tracking tests.');

            return false;
        }

        $this->info('Task tracking tests copied successfully.');

        // Copy factories
        $factoriesStubPath = __DIR__.'/../../../resources/stubs/task-tracking/database/factories';
        $factoriesDestinationPath = database_path('factories');

        if (! $this->copyDirectory($factoriesStubPath, $factoriesDestinationPath)) {
            $this->error('Failed to copy task tracking factories.');

            return false;
        }

        $this->info('Task tracking factories copied successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Copying task tracking tests and factories';
    }
}
