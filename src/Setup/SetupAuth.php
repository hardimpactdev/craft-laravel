<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\Auth\CopyAuthTestsTask;
use HardImpact\Craft\Setup\Auth\CopyAuthViewsTask;
use HardImpact\Craft\Setup\Auth\CopyFortifyFilesTask;
use HardImpact\Craft\Setup\Auth\InstallFortifyTask;
use HardImpact\Craft\Setup\Auth\InstallVueInputOtpTask;
use HardImpact\Craft\Setup\Auth\PublishMigrationsTask;
use HardImpact\Craft\Setup\Auth\RegisterFortifyServiceProviderTask;
use HardImpact\Craft\Setup\Auth\UpdateUserModelTask;
use Illuminate\Filesystem\Filesystem;

class SetupAuth extends Setup
{
    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [
        InstallFortifyTask::class,
        InstallVueInputOtpTask::class,
        CopyFortifyFilesTask::class,
        RegisterFortifyServiceProviderTask::class,
        UpdateUserModelTask::class,
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
