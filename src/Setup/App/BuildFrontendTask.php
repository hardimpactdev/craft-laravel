<?php

namespace HardImpact\Craft\Setup\App;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Process;

class BuildFrontendTask extends Task
{
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    public function run(): bool
    {
        $result = Process::timeout(120)->path(base_path())->run('vp build');

        if (! $result->successful()) {
            $this->error('Failed to build frontend assets.');
            $this->error($result->errorOutput());

            return false;
        }

        $this->info('Frontend assets built.');

        return true;
    }

    public function description(): string
    {
        return 'Building frontend assets';
    }
}
