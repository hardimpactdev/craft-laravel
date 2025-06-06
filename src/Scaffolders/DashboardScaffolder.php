<?php

namespace Livtoff\Laravel\Scaffolders;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Auth\AddAuthRoutesTask;
use Livtoff\Laravel\Scaffolders\Auth\CopyAuthControllersTask;
use Livtoff\Laravel\Scaffolders\Auth\CopyAuthRequestsTask;
use Livtoff\Laravel\Scaffolders\Auth\CopyAuthViewsTask;
use Livtoff\Laravel\Scaffolders\Auth\PublishMigrationsTask;

class DashboardScaffolder extends Scaffolder
{
    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [
        // CopyDashboardControllersTask::class,
        // CopyDashboardViewsTask::class,
        // AddDashboardRoutesTask::class,
    ];

    /**
     * Create a new scaffolder instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct($filesystem);
    }
}
