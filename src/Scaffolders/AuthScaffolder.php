<?php

namespace Livtoff\Laravel\Scaffolders;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Auth\CopyAuthControllersTask;
use Livtoff\Laravel\Scaffolders\Auth\CopyAuthRequestsTask;
use Livtoff\Laravel\Scaffolders\Auth\CopyAuthTestsTask;
use Livtoff\Laravel\Scaffolders\Auth\CopyAuthViewsTask;
use Livtoff\Laravel\Scaffolders\Auth\PublishMigrationsTask;

class AuthScaffolder extends Scaffolder
{
    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [
        CopyAuthControllersTask::class,
        CopyAuthRequestsTask::class,
        CopyAuthViewsTask::class,
        CopyAuthTestsTask::class,
        PublishMigrationsTask::class,
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
