<?php

namespace Livtoff\Laravel\Scaffolders;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Cms\AddFilamentBuildCssCommandTask;
use Livtoff\Laravel\Scaffolders\Cms\CopyAppClassTask;
use Livtoff\Laravel\Scaffolders\Cms\CopyCmsFilesTask;
use Livtoff\Laravel\Scaffolders\Cms\InstallFilamentComposerPackageTask;
use Livtoff\Laravel\Scaffolders\Cms\InstallNpmPackagesTask;
use Livtoff\Laravel\Scaffolders\Cms\RegisterFilamentServiceProviderTask;
use Livtoff\Laravel\Scaffolders\Cms\RunFilamentBuildCssTask;

class CmsScaffolder extends Scaffolder
{
    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [
        CopyAppClassTask::class,
        InstallFilamentComposerPackageTask::class,
        CopyCmsFilesTask::class,
        RegisterFilamentServiceProviderTask::class,
        InstallNpmPackagesTask::class,
        AddFilamentBuildCssCommandTask::class,
        RunFilamentBuildCssTask::class,
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