<?php

namespace Livtoff\Laravel\Scaffolders\App;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\AuthScaffolder;
use Livtoff\Laravel\Scaffolders\Tasks\Task;

class RunAuthScaffolderTask extends Task
{
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
        $this->info('Running Auth scaffolder...');

        $authScaffolder = new AuthScaffolder($this->filesystem);

        // Set the command if available so the auth scaffolder can output messages
        if ($this->command) {
            $authScaffolder->setCommand($this->command);
        }

        try {
            $exitCode = $authScaffolder->scaffold();

            if ($exitCode === 0) {
                $this->info('Auth scaffolder completed successfully.');

                return true;
            } else {
                $this->error('Auth scaffolder failed with exit code: '.$exitCode);

                return false;
            }
        } catch (\Exception $e) {
            $this->error('Auth scaffolder failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Running Auth scaffolder...';
    }
}
