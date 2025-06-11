<?php

namespace Livtoff\Laravel\Scaffolders;

use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\MultiLanguage\CopyLangDirectoryTask;
use Livtoff\Laravel\Scaffolders\MultiLanguage\InstallI18nPackageTask;
use Livtoff\Laravel\Scaffolders\MultiLanguage\UpdateAppTsImportTask;
use Livtoff\Laravel\Scaffolders\MultiLanguage\UpdateAppTsUseI18nTask;
use Livtoff\Laravel\Scaffolders\MultiLanguage\UpdateViteConfigImportTask;
use Livtoff\Laravel\Scaffolders\MultiLanguage\UpdateViteConfigPluginTask;
use Livtoff\Laravel\Scaffolders\MultiLanguage\UpdateViteConfigAutoImportTask;
use Livtoff\Laravel\Scaffolders\MultiLanguage\CopyExamplePageTask;

class MultilanguageScaffolder extends Scaffolder
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
     * Create a new scaffolder instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct($filesystem);
    }
}