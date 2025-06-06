<?php

namespace Livtoff\Laravel\Scaffolders\Cms;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Tasks\Task;

class AddFilamentBuildCssCommandTask extends Task
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
        $packageJsonPath = base_path('package.json');
        
        if (!$this->filesystem->exists($packageJsonPath)) {
            $this->error('package.json not found');
            return false;
        }

        $packageJson = json_decode($this->filesystem->get($packageJsonPath), true);
        
        if (!is_array($packageJson)) {
            $this->error('Failed to parse package.json');
            return false;
        }

        // Add the filament-build-css command to scripts
        if (!isset($packageJson['scripts'])) {
            $packageJson['scripts'] = [];
        }

        $command = 'npx tailwindcss@3 --input ./resources/css/filament/admin/theme.css --output ./public/css/filament/admin/theme.css --config ./resources/css/filament/admin/tailwind.config.js --minify';
        
        if (isset($packageJson['scripts']['filament-build-css']) && $packageJson['scripts']['filament-build-css'] === $command) {
            $this->info('filament-build-css command already exists in package.json');
            return true;
        }

        $packageJson['scripts']['filament-build-css'] = $command;

        $newContent = json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";

        if ($this->filesystem->put($packageJsonPath, $newContent) === false) {
            $this->error('Failed to update package.json');
            return false;
        }

        $this->info('Added filament-build-css command to package.json');
        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Adding filament-build-css command to package.json';
    }
}