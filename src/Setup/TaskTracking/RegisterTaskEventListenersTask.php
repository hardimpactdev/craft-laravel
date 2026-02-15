<?php

namespace HardImpact\Craft\Setup\TaskTracking;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class RegisterTaskEventListenersTask extends Task
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
        $providerPath = app_path('Providers/EventServiceProvider.php');

        if (! $this->filesystem->exists($providerPath)) {
            $this->error('EventServiceProvider not found.');

            return false;
        }

        $content = $this->filesystem->get($providerPath);

        // Check if already registered
        if (str_contains($content, 'TaskStarted::class')) {
            $this->info('Task event listeners already registered.');

            return true;
        }

        // Add imports
        $imports = "use App\\Events\\TaskStarted;\nuse App\\Events\\TaskSentToReview;\nuse App\\Events\\TaskReviewed;\nuse App\\Listeners\\RecordTaskEvent;";

        if (! str_contains($content, 'use App\\Events\\TaskStarted;')) {
            $content = str_replace(
                'namespace App\\Providers;',
                "namespace App\\Providers;\n\n".$imports,
                $content
            );
        }

        // Find the $listen array and add event listeners
        $listenerEntries = "\n        TaskStarted::class => [\n            [RecordTaskEvent::class, 'handleTaskStarted'],\n        ],\n        TaskSentToReview::class => [\n            [RecordTaskEvent::class, 'handleTaskSentToReview'],\n        ],\n        TaskReviewed::class => [\n            [RecordTaskEvent::class, 'handleTaskReviewed'],\n        ],";

        // Try to add inside existing $listen array
        if (preg_match('/protected \$listen = \[/', $content)) {
            $content = preg_replace(
                '/(protected \$listen = \[)/',
                '$1'.$listenerEntries,
                $content
            );
        } else {
            // Add $listen property before class methods
            $content = preg_replace(
                '/(class EventServiceProvider extends ServiceProvider\s*\{)/',
                "$1\n    /**\n     * The event to listener mappings for the application.\n     *\n     * @var array\u003cclass-string, array\u003cint, class-string\u003e\u003e\n     */\n    protected \$listen = [".$listenerEntries."\n    ];",
                $content
            );
        }

        if ($this->filesystem->put($providerPath, $content) === false) {
            $this->error('Failed to update EventServiceProvider.');

            return false;
        }

        $this->info('Task event listeners registered in EventServiceProvider.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Registering task event listeners in EventServiceProvider';
    }
}
