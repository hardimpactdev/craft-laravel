<?php

namespace Livtoff\Laravel\Scaffolders\Tasks;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class GenerateRoutesTask extends Task
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
        $this->info('Generating routes from controller attributes...');

        try {
            // Run the route-maker:make command
            $exitCode = Artisan::call('route-maker:make', [], $this->command ? $this->command->getOutput() : null);

            if ($exitCode === 0) {
                $this->info('Routes generated successfully.');

                return true;
            } else {
                $this->error('Failed to generate routes. Exit code: '.$exitCode);

                return false;
            }
        } catch (\Exception $e) {
            $this->error('Failed to generate routes: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Generating routes from controller attributes...';
    }
}
