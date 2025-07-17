<?php

namespace HardImpact\Liftoff\Setup\Cms;

use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallNpmPackagesTask extends Task
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
        $this->info('Installing npm packages via bun...');

        $packages = ['@tailwindcss/forms', '@tailwindcss/typography'];

        $process = new Process(array_merge(['bun', 'add', '-D'], $packages), base_path());
        $process->setTimeout(300); // 5 minutes timeout

        if ($this->command) {
            $process->run(function ($type, $buffer) {
                $this->command->line($buffer);
            });
        } else {
            $process->run();
        }

        if (! $process->isSuccessful()) {
            $this->error('Failed to install npm packages: '.$process->getErrorOutput());

            return false;
        }

        $this->info('NPM packages installed successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Installing npm packages (@tailwindcss/forms and @tailwindcss/typography)';
    }
}
