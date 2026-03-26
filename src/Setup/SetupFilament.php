<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\Cms\CopyCmsFilesTask;
use HardImpact\Craft\Setup\Cms\InstallFilamentComposerPackageTask;
use HardImpact\Craft\Setup\Cms\InstallNpmPackagesTask;
use HardImpact\Craft\Setup\Cms\RegisterFilamentServiceProviderTask;
use HardImpact\Craft\Setup\Cms\RunFilamentBuildCssTask;
use Illuminate\Filesystem\Filesystem;

class SetupFilament extends Setup
{
    /**
     * The tasks to run.
     *
     * Installs Filament admin panel. Can be run standalone or after `craft:setup app`.
     *
     * @var array
     */
    protected $tasks = [
        InstallFilamentComposerPackageTask::class,
        CopyCmsFilesTask::class,
        RegisterFilamentServiceProviderTask::class,
        InstallNpmPackagesTask::class,
        RunFilamentBuildCssTask::class,
    ];

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct($filesystem);
    }
}
