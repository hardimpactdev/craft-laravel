<?php

namespace HardImpact\Craft\Setup\Auth;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateUserModelTask extends Task
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
        $userModelPath = app_path('Models/User.php');

        if (! $this->filesystem->exists($userModelPath)) {
            $this->error('User model not found at '.$userModelPath);

            return false;
        }

        $content = $this->filesystem->get($userModelPath);

        // Check if TwoFactorAuthenticatable is already added
        if (str_contains($content, 'TwoFactorAuthenticatable')) {
            $this->info('User model already has TwoFactorAuthenticatable trait.');

            return true;
        }

        // Add the use statement for the trait
        $useStatement = "use Laravel\\Fortify\\TwoFactorAuthenticatable;\n";

        // Find the namespace line and add use statement after it
        if (preg_match('/^namespace [^;]+;/m', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $insertPosition = $matches[0][1] + strlen($matches[0][0]);
            $content = substr($content, 0, $insertPosition)."\n\n".$useStatement.substr($content, $insertPosition);
        }

        // Add the trait to the class
        // Look for existing use statements in the class
        if (preg_match('/class User[^{]*\{[^}]*use ([^;]+);/s', $content, $matches)) {
            // There are existing traits, add TwoFactorAuthenticatable
            $existingTraits = $matches[1];
            $newTraits = $existingTraits.', TwoFactorAuthenticatable';
            $content = str_replace('use '.$existingTraits.';', 'use '.$newTraits.';', $content);
        } else {
            // No traits, add the use statement after the opening brace
            $content = preg_replace(
                '/(class User[^{]*\{)/',
                "$1\n    use TwoFactorAuthenticatable;\n",
                $content
            );
        }

        // Add two_factor fields to hidden array if not present
        if (! str_contains($content, 'two_factor_secret')) {
            $content = preg_replace(
                "/'password',/",
                "'password',\n        'two_factor_secret',\n        'two_factor_recovery_codes',",
                $content
            );
        }

        // Add two_factor_confirmed_at to casts if not present
        if (! str_contains($content, 'two_factor_confirmed_at')) {
            // Try to add to casts method
            if (preg_match('/protected function casts\(\)[^{]*\{[^}]*return\s*\[/s', $content)) {
                $content = preg_replace(
                    "/(protected function casts\(\)[^{]*\{[^}]*return\s*\[)/s",
                    "$1\n            'two_factor_confirmed_at' => 'datetime',",
                    $content
                );
            }
        }

        if ($this->filesystem->put($userModelPath, $content) === false) {
            $this->error('Failed to update User model.');

            return false;
        }

        $this->info('User model updated with TwoFactorAuthenticatable trait.');

        return true;
    }

    /**
     * Get the task description.
     */
    public function description(): string
    {
        return 'Updating User model with TwoFactorAuthenticatable trait';
    }
}
