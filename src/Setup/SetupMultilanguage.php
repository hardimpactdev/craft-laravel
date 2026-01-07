<?php

namespace HardImpact\Liftoff\Setup;

use HardImpact\Liftoff\Setup\MultiLanguage\CopyExamplePageTask;
use HardImpact\Liftoff\Setup\MultiLanguage\CopyLangDirectoryTask;
use HardImpact\Liftoff\Setup\Tasks\GenerateRoutesTask;
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
