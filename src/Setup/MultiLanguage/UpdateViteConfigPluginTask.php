<?php

namespace HardImpact\Liftoff\Setup\MultiLanguage;

use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateViteConfigPluginTask extends Task
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
        $filePath = base_path('vite.config.ts');

        if (! $this->filesystem->exists($filePath)) {
            $this->error('vite.config.ts file not found at: '.$filePath);

            return false;
        }

        $content = $this->filesystem->get($filePath);

        // Check if i18n() is already in plugins
        if (preg_match('/plugins:\s*\[[^]]*i18n\s*\(\s*\)/s', $content)) {
            $this->info('i18n() already exists in vite.config.ts plugins');

            return true;
        }

        // Find the plugins array
        if (preg_match('/plugins:\s*\[/s', $content, $pluginsMatch, PREG_OFFSET_CAPTURE)) {
            $pluginsStartPos = $pluginsMatch[0][1] + strlen($pluginsMatch[0][0]);

            // Find the closing bracket of the plugins array using bracket counting
            $depth = 1;
            $currentPos = $pluginsStartPos;
            $pluginsEndPos = null;

            while ($depth > 0 && $currentPos < strlen($content)) {
                $char = $content[$currentPos];
                if ($char === '[') {
                    $depth++;
                } elseif ($char === ']') {
                    $depth--;
                    if ($depth === 0) {
                        $pluginsEndPos = $currentPos;
                        break;
                    }
                }
                $currentPos++;
            }

            if ($pluginsEndPos !== null) {
                // Extract the plugins array content
                $pluginsContent = substr($content, $pluginsStartPos, $pluginsEndPos - $pluginsStartPos);

                // Find a good position to insert - after the first plugin (laravel)
                // Look for the closing parenthesis of laravel() or any plugin function call
                $insertPos = null;

                // Find the first plugin function call closing parenthesis
                if (preg_match('/\w+\s*\([^)]*\)(?:\s*,)?/s', $pluginsContent, $firstPluginMatch, PREG_OFFSET_CAPTURE)) {
                    // $firstPluginMatch[0] contains the full match array with [0] = matched string, [1] = offset
                    $insertPos = $pluginsStartPos + $firstPluginMatch[0][1] + strlen($firstPluginMatch[0][0]);

                    // Build the new plugin string
                    $newPlugin = '
        i18n(),';

                    // Insert the new plugin
                    $newContent = substr($content, 0, $insertPos).$newPlugin.substr($content, $insertPos);

                    if ($this->filesystem->put($filePath, $newContent) === false) {
                        $this->error('Failed to update vite.config.ts file.');

                        return false;
                    }

                    $this->info('Added i18n() to vite.config.ts plugins');

                    return true;
                }
            }
        }

        $this->error('Could not find plugins array in vite.config.ts');

        return false;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Adding i18n() to vite.config.ts plugins';
    }
}
