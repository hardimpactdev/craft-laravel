<?php

namespace Livtoff\Laravel\Setup;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Setup\App\CopyAppClassTask;
use Livtoff\Laravel\Setup\App\CopyAppControllersTask;
use Livtoff\Laravel\Setup\App\CopyAppMiddlewareTask;
use Livtoff\Laravel\Setup\App\CopyAppRequestsTask;
use Livtoff\Laravel\Setup\App\CopyAppTestsTask;
use Livtoff\Laravel\Setup\App\CopyAppViewsTask;
use Livtoff\Laravel\Setup\App\RunCmsSetupTask;
use Livtoff\Laravel\Setup\App\RunSetupAuthTask;
use Livtoff\Laravel\Setup\Tasks\GenerateRoutesTask;

class SetupApp extends Setup
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
        RunSetupAuthTask::class,
        RunCmsSetupTask::class,
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
