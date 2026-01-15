<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\App\CopyAppClassTask;
use HardImpact\Craft\Setup\App\CopyAppControllersTask;
use HardImpact\Craft\Setup\App\CopyAppMiddlewareTask;
use HardImpact\Craft\Setup\App\CopyAppRequestsTask;
use HardImpact\Craft\Setup\App\CopyAppTestsTask;
use HardImpact\Craft\Setup\App\CopyAppViewsTask;
use HardImpact\Craft\Setup\Tasks\GenerateRoutesTask;
use Illuminate\Filesystem\Filesystem;

class SetupDashboard extends Setup
{
    /**
     * The tasks to run.
     *
     * Sets up dashboard and settings pages.
     * Note: Requires auth to be set up first. Run `craft:setup auth` before this.
     *
     * @var array
     */
    protected $tasks = [
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
