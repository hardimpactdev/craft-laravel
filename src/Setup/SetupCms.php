<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\Cms\CopyAppClassTask;
use HardImpact\Craft\Setup\Cms\CopyCmsFilesTask;
use HardImpact\Craft\Setup\Cms\InstallFilamentComposerPackageTask;
use HardImpact\Craft\Setup\Cms\InstallNpmPackagesTask;
use HardImpact\Craft\Setup\Cms\RegisterFilamentServiceProviderTask;
use HardImpact\Craft\Setup\Cms\RunFilamentBuildCssTask;
use HardImpact\Craft\Setup\Cms\RunSetupAuthTask;
use HardImpact\Craft\Setup\Tasks\GenerateRoutesTask;
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
