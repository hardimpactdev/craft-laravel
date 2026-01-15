<?php

namespace HardImpact\Craft\Setup;

use HardImpact\Craft\Setup\MultiLanguage\CopyExamplePageTask;
use HardImpact\Craft\Setup\MultiLanguage\CopyLangDirectoryTask;
use HardImpact\Craft\Setup\Tasks\GenerateRoutesTask;
use Illuminate\Filesystem\Filesystem;

class SetupMultilanguage extends Setup
{
    /**
     * The tasks to run.
     *
     * Note: Vite i18n configuration is already included in the starterkit.
     * This setup only copies language files and example components.
     *
     * @var array
     */
    protected $tasks = [
        CopyLangDirectoryTask::class,
        CopyExamplePageTask::class,
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
