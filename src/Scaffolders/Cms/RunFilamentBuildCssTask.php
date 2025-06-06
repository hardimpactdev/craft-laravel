<?php

namespace Livtoff\Laravel\Scaffolders\Cms;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Tasks\Task;
use Symfony\Component\Process\Process;

class RunFilamentBuildCssTask extends Task
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
        $this->info('Building Filament CSS...');

        // Use bun to run the npm script
        $process = new Process(['bun', 'run', 'filament-build-css'], base_path());
        $process->setTimeout(120); // 2 minutes timeout

        if ($this->command) {
            $process->run(function ($type, $buffer) {
                $this->command->line($buffer);
            });
        } else {
            $process->run();
        }

        if (! $process->isSuccessful()) {
            $this->error('Failed to build Filament CSS: '.$process->getErrorOutput());

            return false;
        }

        $this->info('Filament CSS built successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Building Filament CSS';
    }
}
