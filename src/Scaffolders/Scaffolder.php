<?php

namespace Livtoff\Laravel\Scaffolders;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

abstract class Scaffolder implements ScaffolderInterface
{
    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * The command instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $command;

    /**
     * The tasks to run.
     *
     * @var array
     */
    protected $tasks = [];

    /**
     * Create a new scaffolder instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Set the command instance.
     *
     * @return $this
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Run the scaffolding process.
     */
    public function scaffold(): int
    {
        if ($this->command) {
            $this->command->info('Starting scaffolding...');
        }

        foreach ($this->tasks as $taskClass) {
            // Make sure we're passing the right dependencies
            $task = app()->makeWith($taskClass, [
                'filesystem' => $this->filesystem,
                'command' => $this->command,
            ]);

            if ($this->command) {
                $this->command->info("Task: {$task->description()}");
            }

            if (! $task->run()) {
                if ($this->command) {
                    $this->command->error("Task failed: {$task->description()}");
                }

                return 1;
            }
        }

        if ($this->command) {
            $this->command->info('Scaffolding completed successfully!');
        }

        return 0;
    }

    protected function info($message)
    {
        if ($this->command) {
            $this->command->info($message);
        }
    }

    protected function error($message)
    {
        if ($this->command) {
            $this->command->error($message);
        }
    }
}
