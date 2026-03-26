<?php

namespace HardImpact\Craft\Setup\Auth;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;

class InstallAuthFrontendTask extends Task
{
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    public function run(): bool
    {
        $result = Process::timeout(120)->path(base_path())->run(
            'npx shadcn add @craft/auth-pages --yes --overwrite'
        );

        if (! $result->successful()) {
            $this->error('Failed to install auth pages from @craft registry.');
            $this->error($result->errorOutput());

            return false;
        }

        $this->info('Auth pages installed from @craft registry.');

        return true;
    }

    public function description(): string
    {
        return 'Installing auth pages from @craft registry';
    }
}
