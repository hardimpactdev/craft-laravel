<?php

namespace HardImpact\Craft\Setup\Auth;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class InstallVueInputOtpTask extends Task
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
        $this->info('Installing vue-input-otp package...');

        $process = new Process(['bun', 'add', 'vue-input-otp'], base_path());
        $process->setTimeout(120);

        if ($this->command) {
            $process->run(function ($type, $buffer) {
                $this->command->line($buffer);
            });
        } else {
            $process->run();
        }

        if (! $process->isSuccessful()) {
            $this->error('Failed to install vue-input-otp: '.$process->getErrorOutput());

            return false;
        }

        $this->info('vue-input-otp installed successfully.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Installing vue-input-otp npm package';
    }
}
