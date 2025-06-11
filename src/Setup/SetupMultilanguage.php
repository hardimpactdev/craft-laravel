<?php

namespace Livtoff\Laravel\Setup;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Setup\MultiLanguage\CopyExamplePageTask;
use Livtoff\Laravel\Setup\MultiLanguage\CopyLangDirectoryTask;
use Livtoff\Laravel\Setup\MultiLanguage\InstallI18nPackageTask;
use Livtoff\Laravel\Setup\MultiLanguage\UpdateAppTsImportTask;
use Livtoff\Laravel\Setup\MultiLanguage\UpdateAppTsUseI18nTask;
use Livtoff\Laravel\Setup\MultiLanguage\UpdateViteConfigAutoImportTask;
use Livtoff\Laravel\Setup\MultiLanguage\UpdateViteConfigImportTask;
use Livtoff\Laravel\Setup\MultiLanguage\UpdateViteConfigPluginTask;

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
