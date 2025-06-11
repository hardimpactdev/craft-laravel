<?php

namespace Livtoff\Laravel\Setup\MultiLanguage;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Setup\Tasks\Task;

class UpdateViteConfigAutoImportTask extends Task
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

        // Check if laravel-vue-i18n is already in autoimport
        if (str_contains($content, '"laravel-vue-i18n"')) {
            $this->info('laravel-vue-i18n already exists in autoimport configuration');

            return true;
        }

        // Find the autoimport section and locate the imports array
        // We need to find the last item in the imports array to add our new import after it

        // First, let's find the autoimport section
        if (preg_match('/autoimport\s*\(\s*\{[^}]*imports:\s*\[/s', $content, $autoImportMatch, PREG_OFFSET_CAPTURE)) {
            $importsStartPos = $autoImportMatch[0][1] + strlen($autoImportMatch[0][0]);

            // Now find the closing bracket of the imports array
            $depth = 1;
            $currentPos = $importsStartPos;
            $importsEndPos = null;

            while ($depth > 0 && $currentPos < strlen($content)) {
                $char = $content[$currentPos];
                if ($char === '[') {
                    $depth++;
                } elseif ($char === ']') {
                    $depth--;
                    if ($depth === 0) {
                        $importsEndPos = $currentPos;
                        break;
                    }
                }
                $currentPos++;
            }

            if ($importsEndPos !== null) {
                // Extract the imports array content
                $importsContent = substr($content, $importsStartPos, $importsEndPos - $importsStartPos);

                // Find the last closing brace or quote to insert our new import after
                $lastItemMatch = null;
                if (preg_match_all('/(\}|"[^"]+")(\s*,)?/s', $importsContent, $matches, PREG_OFFSET_CAPTURE)) {
                    $lastItemMatch = end($matches[0]);
                }

                if ($lastItemMatch !== null) {
                    // Insert position is after the entire match (including any comma)
                    $insertPos = $importsStartPos + $lastItemMatch[1] + strlen($lastItemMatch[0]);

                    // Build the new import string (always with a leading comma since we're adding to an existing array)
                    $newImport = '
                {
                    "laravel-vue-i18n": ["trans"],
                }';

                    // Insert the new import
                    $newContent = substr($content, 0, $insertPos).$newImport.substr($content, $insertPos);

                    if ($this->filesystem->put($filePath, $newContent) === false) {
                        $this->error('Failed to update vite.config.ts file.');

                        return false;
                    }

                    $this->info('Added laravel-vue-i18n to autoimport configuration');

                    return true;
                }
            }
        }

        $this->error('Could not find autoimport imports section in vite.config.ts');

        return false;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Adding laravel-vue-i18n to autoimport configuration in vite.config.ts';
    }
}
