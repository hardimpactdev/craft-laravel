<?php

use Illuminate\Filesystem\Filesystem;

// Tests for stub file content and namespace placeholders

it('generates auth test files with proper namespace replacement', function () {
    $filesystem = new Filesystem;

    // Get package root path from test directory
    $packageRoot = dirname(__DIR__, 2);

    // Simulate the task's behavior - read from the actual stub location
    $stubPath = $packageRoot.'/resources/stubs/auth/tests/Feature/Auth/RegistrationTest.php';
    expect($filesystem->exists($stubPath))->toBeTrue();

    $stubContent = file_get_contents($stubPath);

    // Verify the stub contains the placeholder pattern for use statements
    // The placeholder is {{namespace}}App which becomes App\App after replacement
    expect($stubContent)->toContain('{{namespace}}App');
    expect($stubContent)->not->toContain('use App\\App;');

    // Test with standard Laravel namespace (app()->getNamespace() returns 'App\\')
    $namespaceWithBackslash = 'App\\';
    $replacedContent = str_replace('{{namespace}}', $namespaceWithBackslash, $stubContent);

    // Verify the replacement works correctly: {{namespace}}App becomes App\App
    expect($replacedContent)->toContain('use App\\App;');
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

    // Test with trimmed namespace (CopyAppClassTask uses rtrim)
    $trimmedNamespace = 'App';
    $replacedContent = str_replace('{{namespace}}', $trimmedNamespace, $stubContent);

    // The namespace should be properly formed without trailing backslash
    expect($replacedContent)->toContain('namespace App;');
    expect($replacedContent)->not->toContain('namespace App\\;');
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

// Tests for task behavior and namespace replacement logic

describe('CopyAppClassTask', function () {
    it('replaces namespace placeholder correctly when copying App.php', function () {
        $filesystem = new Filesystem;
        $tempDir = sys_get_temp_dir().'/craft-test-'.uniqid();

        // Create temp directory structure
        $filesystem->makeDirectory($tempDir.'/app', 0755, true);

        // Read original stub
        $packageRoot = dirname(__DIR__, 2);
        $stubContent = file_get_contents($packageRoot.'/resources/stubs/app/app/App.php');

        // Verify stub has placeholder
        expect($stubContent)->toContain('{{namespace}}');

        // Test with trimmed namespace (as CopyAppClassTask does with rtrim)
        $testCases = [
            'App' => 'namespace App;',
            'Custom' => 'namespace Custom;',
            'MyVendor\\MyApp' => 'namespace MyVendor\\MyApp;',
        ];

        foreach ($testCases as $namespace => $expectedNamespace) {
            $replacedContent = str_replace('{{namespace}}', $namespace, $stubContent);

            expect($replacedContent)->toContain($expectedNamespace);
            expect($replacedContent)->not->toContain('{{namespace}}');

            // Verify the resulting PHP is valid syntax
            $tempFile = $tempDir.'/app/App_'.str_replace('\\', '_', $namespace).'.php';
            file_put_contents($tempFile, $replacedContent);

            // Check PHP syntax is valid
            exec('php -l '.escapeshellarg($tempFile).' 2>&1', $output, $exitCode);
            expect($exitCode)->toBe(0, 'PHP syntax error in generated file for namespace: '.$namespace);
        }

        // Cleanup
        $filesystem->deleteDirectory($tempDir);
    });

    it('handles edge case namespaces correctly', function () {
        $filesystem = new Filesystem;
        $packageRoot = dirname(__DIR__, 2);
        $stubContent = file_get_contents($packageRoot.'/resources/stubs/app/app/App.php');

        // Test edge cases with trimmed namespaces
        $edgeCases = [
            // Single level
            'App' => 'namespace App;',
            // Deep nesting (trimmed)
            'A\\B\\C\\D\\E' => 'namespace A\\B\\C\\D\\E;',
        ];

        foreach ($edgeCases as $namespace => $expected) {
            $replacedContent = str_replace('{{namespace}}', $namespace, $stubContent);
            expect($replacedContent)->toContain($expected);
        }
    });
});

describe('CopyAuthTestsTask', function () {
    it('replaces namespace placeholder in all auth test files', function () {
        $filesystem = new Filesystem;
        $packageRoot = dirname(__DIR__, 2);

        $testFiles = [
            'Feature/Auth/RegistrationTest.php',
            'Feature/Auth/EmailVerificationTest.php',
        ];

        foreach ($testFiles as $testFile) {
            $stubPath = $packageRoot.'/resources/stubs/auth/tests/'.$testFile;
            expect($filesystem->exists($stubPath))->toBeTrue($testFile.' should exist');

            $content = file_get_contents($stubPath);

            // Should contain placeholder pattern for use statements
            // The pattern is {{namespace}}App which requires namespace with trailing backslash
            expect(strpos($content, '{{namespace}}App') !== false)->toBeTrue(
                $testFile.' should have {{namespace}}App placeholder'
            );

            // Should not contain hardcoded App\App
            expect(strpos($content, 'use App\\App;') !== false)->toBeFalse(
                $testFile.' should not have hardcoded use App\\App;'
            );

            // Test replacement with namespace that includes trailing backslash (as app()->getNamespace() returns)
            $namespaceWithBackslash = 'MyApp\\';
            $replaced = str_replace('{{namespace}}', $namespaceWithBackslash, $content);

            expect(strpos($replaced, 'use MyApp\\App;') !== false)->toBeTrue(
                $testFile.' should have correct use statement after replacement'
            );
            expect(strpos($replaced, '{{namespace}}') !== false)->toBeFalse(
                $testFile.' should not have placeholder after replacement'
            );
        }
    });

    it('generates valid PHP syntax after namespace replacement', function () {
        $filesystem = new Filesystem;
        $tempDir = sys_get_temp_dir().'/craft-test-'.uniqid();
        $filesystem->makeDirectory($tempDir.'/tests/Feature/Auth', 0755, true);

        $packageRoot = dirname(__DIR__, 2);
        $testFiles = [
            'Feature/Auth/RegistrationTest.php',
            'Feature/Auth/EmailVerificationTest.php',
        ];

        foreach ($testFiles as $testFile) {
            $content = file_get_contents($packageRoot.'/resources/stubs/auth/tests/'.$testFile);

            // Replace with a namespace that includes trailing backslash (as app()->getNamespace() returns)
            $content = str_replace('{{namespace}}', 'Custom\\', $content);

            // Write to temp file
            $tempFile = $tempDir.'/tests/'.$testFile;
            $filesystem->ensureDirectoryExists(dirname($tempFile));
            file_put_contents($tempFile, $content);

            // Validate PHP syntax
            exec('php -l '.escapeshellarg($tempFile).' 2>&1', $output, $exitCode);
            expect($exitCode)->toBe(0, 'PHP syntax error in '.$testFile);
        }

        // Cleanup
        $filesystem->deleteDirectory($tempDir);
    });
});

// Integration-like tests that verify the full replacement chain

describe('Namespace Replacement Integration', function () {
    it('correctly handles the standard Laravel App namespace', function () {
        $filesystem = new Filesystem;
        $packageRoot = dirname(__DIR__, 2);

        // Standard Laravel: app()->getNamespace() returns 'App\\' with trailing backslash
        // CopyAppClassTask uses rtrim() to get 'App'
        // CopyAuthTestsTask uses as-is to get 'App\\'

        // Test App.php (uses trimmed namespace)
        $appStub = file_get_contents($packageRoot.'/resources/stubs/app/app/App.php');
        $appResult = str_replace('{{namespace}}', 'App', $appStub);

        expect($appResult)->toContain('namespace App;');
        expect($appResult)->not->toContain('namespace App\\;');

        // Test auth test files (uses namespace with trailing backslash)
        $registrationStub = file_get_contents($packageRoot.'/resources/stubs/auth/tests/Feature/Auth/RegistrationTest.php');
        $registrationResult = str_replace('{{namespace}}', 'App\\', $registrationStub);

        // {{namespace}}App becomes App\App
        expect($registrationResult)->toContain('use App\\App;');
    });

    it('correctly handles custom application namespaces', function () {
        $filesystem = new Filesystem;
        $packageRoot = dirname(__DIR__, 2);

        $customNamespaces = [
            ['trimmed' => 'Acme', 'with_backslash' => 'Acme\\'],
            ['trimmed' => 'Vendor\\Package', 'with_backslash' => 'Vendor\\Package\\'],
        ];

        foreach ($customNamespaces as $ns) {
            // Test App.php (uses trimmed namespace)
            $appStub = file_get_contents($packageRoot.'/resources/stubs/app/app/App.php');
            $appResult = str_replace('{{namespace}}', $ns['trimmed'], $appStub);

            expect($appResult)->toContain('namespace '.$ns['trimmed'].';');

            // Test auth test (uses namespace with trailing backslash)
            $testStub = file_get_contents($packageRoot.'/resources/stubs/auth/tests/Feature/Auth/RegistrationTest.php');
            $testResult = str_replace('{{namespace}}', $ns['with_backslash'], $testStub);

            expect($testResult)->toContain('use '.$ns['with_backslash'].'App;');
        }
    });
});
