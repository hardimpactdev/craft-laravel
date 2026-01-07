<?php

namespace HardImpact\Liftoff\Setup;

use HardImpact\Liftoff\Setup\App\CopyAppClassTask;
use HardImpact\Liftoff\Setup\App\CopyAppControllersTask;
use HardImpact\Liftoff\Setup\App\CopyAppMiddlewareTask;
use HardImpact\Liftoff\Setup\App\CopyAppRequestsTask;
use HardImpact\Liftoff\Setup\App\CopyAppTestsTask;
use HardImpact\Liftoff\Setup\App\CopyAppViewsTask;
use HardImpact\Liftoff\Setup\App\RunSetupAuthTask;
use HardImpact\Liftoff\Setup\Tasks\GenerateRoutesTask;
use Illuminate\Filesystem\Filesystem;

class SetupApp extends Setup
{
    /**
     * The tasks to run.
     *
     * Sets up a full application with authentication, dashboard, and settings.
     * Does NOT include CMS - run `liftoff:setup cms` separately if needed.
     *
     * @var array
     */
    protected $tasks = [
        RunSetupAuthTask::class,
        CopyAppClassTask::class,
        CopyAppControllersTask::class,
        CopyAppMiddlewareTask::class,
        CopyAppRequestsTask::class,
        CopyAppViewsTask::class,
        CopyAppTestsTask::class,
        GenerateRoutesTask::class,
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
