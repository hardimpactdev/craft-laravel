<?php

namespace Livtoff\Laravel\Scaffolders\Auth;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Livtoff\Laravel\Scaffolders\Tasks\Task;

class AddAuthRoutesTask extends Task
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
        $webRoutesPath = base_path('routes/web.php');
        $authRoutesPath = base_path('routes/auth.php');
        $stubPath = __DIR__.'/../../../resources/stubs/auth/routes/auth.php';

        if (! $this->filesystem->exists($stubPath)) {
            $this->error("Routes stub file not found: {$stubPath}");

            return false;
        }

        // Check if auth.php already exists
        if ($this->filesystem->exists($authRoutesPath)) {
            $this->info('Auth routes file already exists. Skipping file creation.');
        } else {
            // Get stub content
            $routeStub = $this->filesystem->get($stubPath);

            // Check if the stub already starts with <?php
            if (str_starts_with(trim($routeStub), '<?php')) {
                // If it does, just add a comment after it
                $routeStub = preg_replace('/^<\?php/', "<?php\n\n// Auth Routes", $routeStub);
            } else {
                // If it doesn't, add the PHP tag and comment
                $routeStub = "<?php\n\n// Auth Routes\n\n".$routeStub;
            }

            // Write to the new route file
            if ($this->filesystem->put($authRoutesPath, $routeStub) === false) {
                $this->error('Failed to create auth routes file.');

                return false;
            }

            $this->info('Created auth routes at routes/auth.php');
        }

        // Now check if web.php already includes the auth.php file
        $webRoutesContent = $this->filesystem->get($webRoutesPath);

        if (str_contains($webRoutesContent, "require __DIR__.'/auth.php'")) {
            $this->info('Auth routes are already included in web.php. Skipping inclusion.');

            return true;
        }

        // Append the include statement to web.php
        $includeStatement = "\n\nrequire __DIR__.'/auth.php';\n";

        if ($this->filesystem->append($webRoutesPath, $includeStatement) === 0) {
            $this->error('Failed to update web.php to include auth routes.');

            return false;
        }

        $this->info('Updated web.php to include auth routes');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Adding authentication routes';
    }
}
