<?php

namespace HardImpact\Craft\Setup\App;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;

class InstallAppFrontendTask extends Task
{
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    public function run(): bool
    {
        $result = Process::timeout(120)->path(base_path())->run(
            'npx shadcn add @craft/settings-pages @craft/dashboard --yes --overwrite'
        );

        if (! $result->successful()) {
            $this->error('Failed to install app pages from @craft registry.');
            $this->error($result->errorOutput());

            return false;
        }

        $this->info('App pages installed from @craft registry.');

        return true;
    }

    public function description(): string
    {
        return 'Installing app pages from @craft registry';
    }
}
