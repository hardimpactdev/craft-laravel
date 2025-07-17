<?php

namespace HardImpact\Liftoff\Setup;

use HardImpact\Liftoff\Setup\Cms\CopyAppClassTask;
use HardImpact\Liftoff\Setup\Cms\CopyCmsFilesTask;
use HardImpact\Liftoff\Setup\Cms\InstallFilamentComposerPackageTask;
use HardImpact\Liftoff\Setup\Cms\InstallNpmPackagesTask;
use HardImpact\Liftoff\Setup\Cms\RegisterFilamentServiceProviderTask;
use HardImpact\Liftoff\Setup\Cms\RunFilamentBuildCssTask;
use HardImpact\Liftoff\Setup\Cms\RunSetupAuthTask;
use HardImpact\Liftoff\Setup\Tasks\GenerateRoutesTask;
use Illuminate\Filesystem\Filesystem;

class SetupCms extends Setup
{
    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [
        CopyAppClassTask::class,
        RunSetupAuthTask::class,
        InstallFilamentComposerPackageTask::class,
        CopyCmsFilesTask::class,
        RegisterFilamentServiceProviderTask::class,
        InstallNpmPackagesTask::class,
        RunFilamentBuildCssTask::class,
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
