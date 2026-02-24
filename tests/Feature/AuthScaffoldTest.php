<?php

use Illuminate\Filesystem\Filesystem;

it('generates auth test files with proper namespace replacement', function () {
    $filesystem = new Filesystem;

    // Get package root path from test directory
    $packageRoot = dirname(__DIR__, 2);

    // Set the namespace to something other than App to test replacement
    $customNamespace = 'Custom\\';

    // Simulate the task's behavior - read from the actual stub location
    $stubPath = $packageRoot.'/resources/stubs/auth/tests/Feature/Auth/RegistrationTest.php';
    expect($filesystem->exists($stubPath))->toBeTrue();

    $stubContent = file_get_contents($stubPath);

    // Verify the stub contains the placeholder
    expect($stubContent)->toContain('{{namespace}}App');
    expect($stubContent)->not->toContain('use App\\App;');

    // Perform the replacement
    $replacedContent = str_replace('{{namespace}}', $customNamespace, $stubContent);

    // Verify the replacement worked correctly
    expect($replacedContent)->toContain('use Custom\\App;');
    expect($replacedContent)->not->toContain('{{namespace}}');
});

it('generates App.php with proper namespace replacement', function () {
    $filesystem = new Filesystem;

    // Get package root path from test directory
    $packageRoot = dirname(__DIR__, 2);

    // Read the stub from the correct location
    $stubPath = $packageRoot.'/resources/stubs/app/app/App.php';
    expect($filesystem->exists($stubPath))->toBeTrue();

    $stubContent = file_get_contents($stubPath);

    // Verify the stub contains the placeholder
    expect($stubContent)->toContain('namespace {{namespace}};');
    expect($stubContent)->not->toContain('namespace App;');

    // Test with different namespaces
    $namespaces = ['App\\', 'Custom\\', 'MyApp\\'];

    foreach ($namespaces as $namespace) {
        $replacedContent = str_replace('{{namespace}}', $namespace, $stubContent);

        // The namespace should be properly formed (with trailing backslash trimmed in actual usage)
        // In actual usage, app()->getNamespace() returns 'App\\' with trailing backslash
        expect($replacedContent)->toContain('namespace '.$namespace.';');
    }
});

it('does not generate App\\Facades\\App references in scaffolded files', function () {
    $filesystem = new Filesystem;

    // Get package root path from test directory
    $packageRoot = dirname(__DIR__, 2);

    // Read all auth stub files
    $authStubsDir = $packageRoot.'/resources/stubs/auth';
    $appStubsDir = $packageRoot.'/resources/stubs/app';

    expect($filesystem->isDirectory($authStubsDir))->toBeTrue();
    expect($filesystem->isDirectory($appStubsDir))->toBeTrue();

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($authStubsDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());

            // Should never contain App\Facades\App
            expect($content)->not->toContain('App\\Facades\\App');
            expect($content)->not->toContain('use App\\Facades');
        }
    }

    // Check app stubs too
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($appStubsDir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());

            expect($content)->not->toContain('App\\Facades\\App');
            expect($content)->not->toContain('use App\\Facades');
        }
    }
});
