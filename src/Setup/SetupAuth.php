<?php

namespace HardImpact\Liftoff\Setup;

use HardImpact\Liftoff\Setup\Auth\CopyAuthControllersTask;
use HardImpact\Liftoff\Setup\Auth\CopyAuthRequestsTask;
use HardImpact\Liftoff\Setup\Auth\CopyAuthTestsTask;
use HardImpact\Liftoff\Setup\Auth\CopyAuthViewsTask;
use HardImpact\Liftoff\Setup\Auth\PublishMigrationsTask;
use Illuminate\Filesystem\Filesystem;

class SetupAuth extends Setup
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
     * Create a new setup instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct($filesystem);
    }
}
