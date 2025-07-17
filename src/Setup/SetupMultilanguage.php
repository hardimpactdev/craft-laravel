<?php

namespace HardImpact\Liftoff\Setup;

use HardImpact\Liftoff\Setup\MultiLanguage\CopyExamplePageTask;
use HardImpact\Liftoff\Setup\MultiLanguage\CopyLangDirectoryTask;
use HardImpact\Liftoff\Setup\MultiLanguage\InstallI18nPackageTask;
use HardImpact\Liftoff\Setup\MultiLanguage\UpdateAppTsImportTask;
use HardImpact\Liftoff\Setup\MultiLanguage\UpdateAppTsUseI18nTask;
use HardImpact\Liftoff\Setup\MultiLanguage\UpdateViteConfigAutoImportTask;
use HardImpact\Liftoff\Setup\MultiLanguage\UpdateViteConfigImportTask;
use HardImpact\Liftoff\Setup\MultiLanguage\UpdateViteConfigPluginTask;
use Illuminate\Filesystem\Filesystem;

class SetupMultilanguage extends Setup
{
    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [
        CopyLangDirectoryTask::class,
        InstallI18nPackageTask::class,
        UpdateAppTsImportTask::class,
        UpdateAppTsUseI18nTask::class,
        UpdateViteConfigImportTask::class,
        UpdateViteConfigPluginTask::class,
        UpdateViteConfigAutoImportTask::class,
        CopyExamplePageTask::class,
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
