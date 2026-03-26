<?php

namespace HardImpact\Craft\Setup\App;

use HardImpact\Craft\Setup\Tasks\Task;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ConfigureAppEntryTask extends Task
{
    public function __construct(Filesystem $filesystem, ?Command $command = null)
    {
        parent::__construct($filesystem, $command);
    }

    public function run(): bool
    {
        $path = resource_path('js/app.tsx');

        $content = <<<'TSX'
import "../css/app.css";

import { createInertiaApp } from "@inertiajs/react";
import { initializeTheme } from "@/hooks/use-appearance";
import AuthLayout from "@/components/auth-layout";
import AppSidebarLayout from "@/components/app-sidebar-layout";
import SettingsLayout from "@/components/settings-layout";

createInertiaApp({
    title: (title) =>
        title
            ? `${title} - ${import.meta.env.VITE_APP_NAME || "Laravel"}`
            : (import.meta.env.VITE_APP_NAME || "Laravel"),
    layout: (name) => {
        switch (true) {
            case name.startsWith("auth/"):
                return AuthLayout;
            case name.startsWith("settings/"):
                return [AppSidebarLayout, SettingsLayout];
            default:
                return AppSidebarLayout;
        }
    },
});

initializeTheme();
TSX;

        file_put_contents($path, $content);

        $this->info('App entry point configured with layout resolver.');

        return true;
    }

    public function description(): string
    {
        return 'Configuring app entry point with layout resolver';
    }
}
