<?php

namespace HardImpact\Liftoff\Setup\Auth;

use HardImpact\Liftoff\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class PublishMigrationsTask extends Task
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
        $this->info('Publishing authentication migrations...');

        // For Spatie's Laravel Package Tools, the tag is "package-name-migrations"
        $exitCode = Artisan::call('vendor:publish', [
            '--tag' => 'laravel-migrations',
            '--force' => true,
        ]);

        if ($exitCode !== Command::SUCCESS) {
            $this->error('Failed to publish migrations using the package tag. Trying alternative method...');

            // Get the package's migration directory
            $packageMigrationPath = __DIR__.'/../../../../database/migrations';
            $destinationPath = database_path('migrations');

            // Check if the directory exists
            if (! $this->filesystem->isDirectory($packageMigrationPath)) {
                $this->error("Migration source directory not found: {$packageMigrationPath}");

                return false;
            }

            // Copy migrations manually
            $files = $this->filesystem->files($packageMigrationPath);
            $hasCopiedFiles = false;

            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $destinationFile = $destinationPath.'/'.date('Y_m_d_His_').substr($fileName, strpos($fileName, '_') + 1);

                // Skip if migration already exists
                if ($this->migrationExists($destinationPath, $fileName)) {
                    $this->info("Migration {$fileName} already exists. Skipping.");

                    continue;
                }

                // Copy the file
                $this->filesystem->copy($file->getPathname(), $destinationFile);
                $this->info("Copied migration: {$fileName}");
                $hasCopiedFiles = true;
            }

            if (! $hasCopiedFiles) {
                $this->info('No new migrations to copy.');
            }

            return true;
        }

        $this->info('Migrations published successfully.');

        return true;
    }

    /**
     * Check if a migration already exists.
     */
    protected function migrationExists(string $directory, string $fileName): bool
    {
        // Extract the descriptive part of the migration name
        $migrationName = substr($fileName, strpos($fileName, '_') + 1);

        // Get all migration files
        $files = $this->filesystem->files($directory);

        // Check if any existing migration contains the same descriptive name
        foreach ($files as $file) {
            $existingName = $file->getFilename();
            if (strpos($existingName, $migrationName) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Publishing authentication migrations';
    }
}
