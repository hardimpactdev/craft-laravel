<?php

namespace Livtoff\Laravel\Setup;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Setup\Cms\CopyAppClassTask;
use Livtoff\Laravel\Setup\Cms\CopyCmsFilesTask;
use Livtoff\Laravel\Setup\Cms\InstallFilamentComposerPackageTask;
use Livtoff\Laravel\Setup\Cms\InstallNpmPackagesTask;
use Livtoff\Laravel\Setup\Cms\RegisterFilamentServiceProviderTask;
use Livtoff\Laravel\Setup\Cms\RunFilamentBuildCssTask;
use Livtoff\Laravel\Setup\Cms\RunSetupAuthTask;
use Livtoff\Laravel\Setup\Tasks\GenerateRoutesTask;

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
