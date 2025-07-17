<?php

namespace HardImpact\Liftoff\Setup\App;

use HardImpact\Liftoff\Setup\Cms\CopyCmsFilesTask;
use HardImpact\Liftoff\Setup\Cms\InstallFilamentComposerPackageTask;
use HardImpact\Liftoff\Setup\Cms\InstallNpmPackagesTask;
use HardImpact\Liftoff\Setup\Cms\RegisterFilamentServiceProviderTask;
use HardImpact\Liftoff\Setup\Cms\RunFilamentBuildCssTask;
use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class RunCmsSetupTask extends Task
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
