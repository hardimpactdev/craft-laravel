<?php

namespace Livtoff\Laravel\Scaffolders;

use Illuminate\Console\Command;

interface ScaffolderInterface
{
    /**
     * Run the scaffolding process.
     *
     * @return int Exit code (0 for success, non-zero for failure)
     */
    public function scaffold(): int;

    /**
     * Set the command instance.
     *
     * @return $this
     */
    public function setCommand(Command $command);
}
