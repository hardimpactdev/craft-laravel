<?php

namespace HardImpact\Liftoff\Setup\Cms;

use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
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
        $process = new Process(['npx', '-y', 'tailwindcss@3', '--input', './resources/css/filament/admin/theme.css', '--output', './public/css/filament/admin/theme.css', '--config', './resources/css/filament/admin/tailwind.config.js', '--minify'], base_path());
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
