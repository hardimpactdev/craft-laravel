<?php

namespace HardImpact\Craft\Setup\Tasks;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class EnsureRegistryConfigTask extends Task
{
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    public function run(): bool
    {
        $path = base_path('components.json');
        $config = file_exists($path)
            ? json_decode(file_get_contents($path), true)
            : [];

        $config['registries'] ??= [];
        $config['registries']['@craft'] ??= 'https://craft-ui.dev/r/{name}.json';

        file_put_contents($path, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('Configured @craft component registry.');

        return true;
    }

    public function description(): string
    {
        return 'Configuring @craft component registry';
    }
}
