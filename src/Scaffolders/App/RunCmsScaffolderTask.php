<?php

namespace Livtoff\Laravel\Scaffolders\App;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Cms\CopyCmsFilesTask;
use Livtoff\Laravel\Scaffolders\Cms\InstallFilamentComposerPackageTask;
use Livtoff\Laravel\Scaffolders\Cms\InstallNpmPackagesTask;
use Livtoff\Laravel\Scaffolders\Cms\RegisterFilamentServiceProviderTask;
use Livtoff\Laravel\Scaffolders\Cms\RunFilamentBuildCssTask;
use Livtoff\Laravel\Scaffolders\Tasks\Task;

class RunCmsScaffolderTask extends Task
{
    /**
     * The CMS-specific tasks to run (excluding App class and Auth scaffolder).
     *
     * @var array
     */
    protected $cmsTasks = [
        InstallFilamentComposerPackageTask::class,
        CopyCmsFilesTask::class,
        RegisterFilamentServiceProviderTask::class,
        InstallNpmPackagesTask::class,
        RunFilamentBuildCssTask::class,
    ];

    /**
     * Create a new task instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    /**
     * Run the task.
     */
    public function run(): bool
    {
        $this->info('Running CMS scaffolder tasks (excluding Auth and App class)...');

        foreach ($this->cmsTasks as $taskClass) {
            $task = app()->makeWith($taskClass, [
                'filesystem' => $this->filesystem,
                'command' => $this->command,
            ]);

            $this->info("Task: {$task->description()}");

            if (! $task->run()) {
                $this->error("Task failed: {$task->description()}");

                return false;
            }
        }

        $this->info('CMS scaffolder tasks completed successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Running CMS scaffolder tasks...';
    }
}
