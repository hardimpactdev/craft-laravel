<?php

namespace Livtoff\Laravel\Scaffolders;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\App\CopyAppClassTask;
use Livtoff\Laravel\Scaffolders\App\CopyAppControllersTask;
use Livtoff\Laravel\Scaffolders\App\CopyAppMiddlewareTask;
use Livtoff\Laravel\Scaffolders\App\CopyAppRequestsTask;
use Livtoff\Laravel\Scaffolders\App\CopyAppTestsTask;
use Livtoff\Laravel\Scaffolders\App\CopyAppViewsTask;
use Livtoff\Laravel\Scaffolders\App\RunAuthScaffolderTask;
use Livtoff\Laravel\Scaffolders\App\RunCmsScaffolderTask;
use Livtoff\Laravel\Scaffolders\Tasks\GenerateRoutesTask;

class AppScaffolder extends Scaffolder
{
    /**
     * The tasks to run.
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
        RunAuthScaffolderTask::class,
        RunCmsScaffolderTask::class,
        GenerateRoutesTask::class,
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
