<?php

namespace HardImpact\Craft\Setup\Tasks;

interface TaskInterface
{
    /**
     * Run the task.
     *
     * @return bool Success status
     */
    public function run(): bool;

    /**
     * Get the task description.
     */
    public function description(): string;
}
