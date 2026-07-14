<?php

declare(strict_types=1);

use HardImpact\Craft\Setup\MultiLanguage\ConfigureI18nTask;
use HardImpact\Craft\Setup\MultiLanguage\CopyExamplePageTask;
use HardImpact\Craft\Setup\MultiLanguage\CopyLangDirectoryTask;
use HardImpact\Craft\Setup\SetupMultilanguage;
use HardImpact\Craft\Setup\Tasks\GenerateRoutesTask;
use Illuminate\Filesystem\Filesystem;

describe('multilanguage scaffold', function () {
    it('ships translations and an example page for the React starterkit', function () {
        $packageRoot = dirname(__DIR__, 2);

        expect("{$packageRoot}/resources/stubs/multi-language/lang/en.json")
            ->toBeFile()
            ->and("{$packageRoot}/resources/stubs/multi-language/lang/nl.json")
            ->toBeFile()
            ->and("{$packageRoot}/resources/stubs/multi-language/resources/js/pages/TranslationExample.tsx")
            ->toBeFile()
            ->and("{$packageRoot}/resources/stubs/multi-language/resources/js/pages/TranslationExample.vue")
            ->not->toBeFile();

        expect(file_get_contents("{$packageRoot}/resources/stubs/multi-language/resources/js/pages/TranslationExample.tsx"))
            ->toContain('@hardimpactdev/craft-ui-react/i18n')
            ->toContain('setLocale')
            ->toContain('useLocale');
    });

    it('configures i18n before generating routes', function () {
        $setup = new SetupMultilanguage(new Filesystem);
        $tasks = new ReflectionProperty($setup, 'tasks');

        expect(class_exists(ConfigureI18nTask::class))->toBeTrue()
            ->and($tasks->getValue($setup))->toBe([
                CopyLangDirectoryTask::class,
                CopyExamplePageTask::class,
                ConfigureI18nTask::class,
                GenerateRoutesTask::class,
            ]);
    });

    it('enables i18n in an empty Craft Vite configuration', function () {
        $filesystem = new Filesystem;
        $originalBasePath = app()->basePath();
        $temporaryBasePath = sys_get_temp_dir().'/craft-i18n-'.uniqid();
        $viteConfig = <<<'TYPESCRIPT'
import { defineCraftConfig } from "@hardimpactdev/craft-ui-react/vite";

export default await defineCraftConfig();
TYPESCRIPT;

        $filesystem->ensureDirectoryExists($temporaryBasePath);
        $filesystem->put("{$temporaryBasePath}/vite.config.ts", $viteConfig);
        app()->setBasePath($temporaryBasePath);

        try {
            $task = new ConfigureI18nTask($filesystem);

            expect($task->run())->toBeTrue()
                ->and($filesystem->get("{$temporaryBasePath}/vite.config.ts"))
                ->toContain('defineCraftConfig({ i18n: true })');
        } finally {
            app()->setBasePath($originalBasePath);
            $filesystem->deleteDirectory($temporaryBasePath);
        }
    });

    it('adds i18n to an existing Craft Vite configuration once', function () {
        $filesystem = new Filesystem;
        $originalBasePath = app()->basePath();
        $temporaryBasePath = sys_get_temp_dir().'/craft-i18n-'.uniqid();
        $viteConfig = <<<'TYPESCRIPT'
import { defineCraftConfig } from "@hardimpactdev/craft-ui-react/vite";

export default await defineCraftConfig({
    wayfinder: {
        formVariants: true,
    },
});
TYPESCRIPT;

        $filesystem->ensureDirectoryExists($temporaryBasePath);
        $filesystem->put("{$temporaryBasePath}/vite.config.ts", $viteConfig);
        app()->setBasePath($temporaryBasePath);

        try {
            $task = new ConfigureI18nTask($filesystem);

            expect($task->run())->toBeTrue()
                ->and($task->run())->toBeTrue();

            $configured = $filesystem->get("{$temporaryBasePath}/vite.config.ts");

            expect($configured)
                ->toContain("defineCraftConfig({\n    i18n: true,")
                ->and(substr_count($configured, 'i18n: true'))
                ->toBe(1);
        } finally {
            app()->setBasePath($originalBasePath);
            $filesystem->deleteDirectory($temporaryBasePath);
        }
    });
});
