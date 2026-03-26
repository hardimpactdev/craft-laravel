<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\Auth\CopyAuthTestsTask;
use HardImpact\Craft\Setup\Auth\CopyFortifyFilesTask;
use HardImpact\Craft\Setup\Auth\InstallAuthFrontendTask;
use HardImpact\Craft\Setup\Auth\InstallFortifyTask;
use HardImpact\Craft\Setup\Auth\PublishMigrationsTask;
use HardImpact\Craft\Setup\Auth\RegisterFortifyServiceProviderTask;
use HardImpact\Craft\Setup\Auth\UpdateUserModelTask;
use HardImpact\Craft\Setup\Tasks\EnsureRegistryConfigTask;
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
        CopyFortifyFilesTask::class,
        RegisterFortifyServiceProviderTask::class,
        UpdateUserModelTask::class,
        EnsureRegistryConfigTask::class,
        InstallAuthFrontendTask::class,
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
