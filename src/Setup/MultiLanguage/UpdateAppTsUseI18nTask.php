<?php

namespace HardImpact\Liftoff\Setup\MultiLanguage;

use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateAppTsUseI18nTask extends Task
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

        if (! $this->filesystem->exists($filePath)) {
            $this->error('app.ts file not found at: '.$filePath);

            return false;
        }

        $content = $this->filesystem->get($filePath);

        // Check if i18n is already configured
        if (str_contains($content, '.use(i18n,')) {
            $this->info('i18n is already configured in app.ts');

            return true;
        }

        // Look for the createApp chain within the setup function
        // This pattern matches createApp({ render: () => h(App, props) }) followed by .use() calls and .mount()
        $pattern = '/(createApp\s*\(\s*\{[^}]+\}\s*\))((?:\s*\.use\s*\([^)]+\))*)(\s*\.mount\s*\([^)]+\))/s';

        if (preg_match($pattern, $content, $matches)) {
            $createAppPart = $matches[1];
            $existingUses = $matches[2];
            $mountPart = $matches[3];

            // Add the i18n configuration
            $i18nConfig = '
            .use(i18n, {
                langs: import.meta.glob("../../lang/*.json", {
                    eager: true,
                }),
            })';

            // Build the new createApp chain
            $newChain = $createAppPart.$existingUses.$i18nConfig.$mountPart;

            // Replace in content
            $newContent = str_replace($matches[0], $newChain, $content);

            if ($this->filesystem->put($filePath, $newContent) === false) {
                $this->error('Failed to update app.ts file.');

                return false;
            }

            $this->info('Added i18n configuration to app.ts');

            return true;
        }

        $this->error('Could not find createApp section in app.ts');

        return false;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Adding i18n configuration to createApp in app.ts';
    }
}
