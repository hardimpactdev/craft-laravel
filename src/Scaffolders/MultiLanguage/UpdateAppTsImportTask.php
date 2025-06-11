<?php

namespace Livtoff\Laravel\Scaffolders\MultiLanguage;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Tasks\Task;

class UpdateAppTsImportTask extends Task
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
        $filePath = resource_path('js/app.ts');

        if (!$this->filesystem->exists($filePath)) {
            $this->error('app.ts file not found at: ' . $filePath);
            return false;
        }

        $content = $this->filesystem->get($filePath);
        $importStatement = 'import { i18n } from "@livtoff/ui";';

        // Check if import already exists
        if (str_contains($content, $importStatement)) {
            $this->info('i18n import already exists in app.ts');
            return true;
        }

        // Find the right place to add the import (after other imports)
        $lines = explode("\n", $content);
        $importAdded = false;
        $lastImportIndex = -1;

        // Find the last import statement
        foreach ($lines as $index => $line) {
            if (str_starts_with(trim($line), 'import ')) {
                $lastImportIndex = $index;
            }
        }

        // Add the import after the last import
        if ($lastImportIndex >= 0) {
            array_splice($lines, $lastImportIndex + 1, 0, $importStatement);
            $importAdded = true;
        } else {
            // If no imports found, add at the beginning
            array_unshift($lines, $importStatement);
            $importAdded = true;
        }

        if ($importAdded) {
            $newContent = implode("\n", $lines);
            if ($this->filesystem->put($filePath, $newContent) === false) {
                $this->error('Failed to update app.ts file.');
                return false;
            }
            $this->info('Added i18n import to app.ts');
            return true;
        }

        $this->error('Failed to add i18n import to app.ts');
        return false;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Adding i18n import to app.ts';
    }
}