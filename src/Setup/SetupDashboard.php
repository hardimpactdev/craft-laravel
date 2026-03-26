<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\App\BuildFrontendTask;
use HardImpact\Craft\Setup\App\ConfigureAppEntryTask;
use HardImpact\Craft\Setup\App\CopyAppClassTask;
use HardImpact\Craft\Setup\App\CopyAppControllersTask;
use HardImpact\Craft\Setup\App\CopyAppMiddlewareTask;
use HardImpact\Craft\Setup\App\CopyAppRequestsTask;
use HardImpact\Craft\Setup\App\CopyAppTestsTask;
use HardImpact\Craft\Setup\App\InstallAppFrontendTask;
use HardImpact\Craft\Setup\Tasks\EnsureRegistryConfigTask;
use HardImpact\Craft\Setup\Tasks\GenerateRoutesTask;
use HardImpact\Craft\Setup\Tasks\RunMigrationsTask;
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
        EnsureRegistryConfigTask::class,
        InstallAppFrontendTask::class,
        ConfigureAppEntryTask::class,
        CopyAppTestsTask::class,
        RunMigrationsTask::class,
        GenerateRoutesTask::class,
        BuildFrontendTask::class,
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
