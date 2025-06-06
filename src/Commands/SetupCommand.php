<?php

namespace Livtoff\Laravel\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SetupCommand extends Command
{
    protected $signature = 'livtoff:setup {type : The type of setup to run (auth, cms, api)}';

    protected $description = 'Scaffold Livtoff features';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $type = $this->argument('type');

        // Get the appropriate scaffolder
        $scaffolder = $this->resolveScaffolder($type);

        if (! $scaffolder) {
            $this->error("Scaffolder for '{$type}' not found.");

            return 1;
        }

        // Pass this command instance to the scaffolder
        $scaffolder->setCommand($this);

        // Run the scaffolder
        return $scaffolder->scaffold();
    }

    protected function resolveScaffolder($type)
    {
        $scaffolderClass = 'Livtoff\\Laravel\\Scaffolders\\'.ucfirst($type).'Scaffolder';

        if (class_exists($scaffolderClass)) {
            // Explicitly create the scaffolder with a Filesystem instance
            return new $scaffolderClass($this->filesystem);
        }

        return null;
    }
}
