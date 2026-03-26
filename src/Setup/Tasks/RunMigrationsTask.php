<?php

namespace HardImpact\Craft\Setup\Tasks;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class RunMigrationsTask extends Task
{
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    public function run(): bool
    {
        $exitCode = Artisan::call('migrate', ['--force' => true]);

        if ($exitCode !== Command::SUCCESS) {
            $this->error('Failed to run migrations.');

            return false;
        }

        $this->info('Migrations completed.');

        return true;
    }

    public function description(): string
    {
        return 'Running database migrations';
    }
}
