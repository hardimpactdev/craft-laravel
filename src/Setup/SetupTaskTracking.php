<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\TaskTracking\CopyTaskTrackingEventsTask;
use HardImpact\Craft\Setup\TaskTracking\CopyTaskTrackingListenersTask;
use HardImpact\Craft\Setup\TaskTracking\CopyTaskTrackingMigrationsTask;
use HardImpact\Craft\Setup\TaskTracking\CopyTaskTrackingModelsTask;
use HardImpact\Craft\Setup\TaskTracking\CopyTaskTrackingObserversTask;
use HardImpact\Craft\Setup\TaskTracking\CopyTaskTrackingTestsTask;
use HardImpact\Craft\Setup\TaskTracking\RegisterTaskEventListenersTask;
use HardImpact\Craft\Setup\TaskTracking\RegisterTaskObserverTask;
use Illuminate\Filesystem\Filesystem;

class SetupTaskTracking extends Setup
{
    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [
        CopyTaskTrackingMigrationsTask::class,
        CopyTaskTrackingModelsTask::class,
        CopyTaskTrackingObserversTask::class,
        CopyTaskTrackingEventsTask::class,
        CopyTaskTrackingListenersTask::class,
        RegisterTaskObserverTask::class,
        RegisterTaskEventListenersTask::class,
        CopyTaskTrackingTestsTask::class,
    ];

    /**
     * Create a new setup instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct($filesystem);
    }
}
