<?php

namespace HardImpact\Craft\Setup\Auth;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallFortifyTask extends Task
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
        $this->info('Installing Laravel Fortify via Composer...');

        $process = new Process(['composer', 'require', 'laravel/fortify:^1.30', '-W'], base_path());
        $process->setTimeout(300); // 5 minutes timeout

        if ($this->command) {
            $process->run(function ($type, $buffer) {
                $this->command->line($buffer);
            });
        } else {
            $process->run();
        }

        if (! $process->isSuccessful()) {
            $this->error('Failed to install Laravel Fortify: '.$process->getErrorOutput());

            return false;
        }

        $this->info('Laravel Fortify installed successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Installing Laravel Fortify package';
    }
}
