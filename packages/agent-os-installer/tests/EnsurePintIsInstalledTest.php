<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Pint is already installed', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Use a general exists mock that returns true for config files
    File::shouldReceive('exists')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock pint.json content (with fully matching rules to avoid update)
    File::shouldReceive('get')
        ->with(base_path('pint.json'))
        ->andReturn(json_encode([
            'preset' => 'laravel',
            'rules' => [
                'declare_strict_types' => true,
                'fully_qualified_strict_types' => true,
                'single_trait_insert_per_statement' => true,
                'array_syntax' => true,
            ],
        ]));

    // Mock phpstan.neon content
    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    // Mock rector.php content
    File::shouldReceive('get')
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    // Allow composer.json to be updated with scripts
    File::shouldReceive('put')
        ->with(base_path('composer.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    // Mock .gitignore content

    File::shouldReceive('get')

        ->with(base_path('.gitignore'))

        ->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('append')

        ->with(base_path('.gitignore'), Mockery::type('string'))

        ->zeroOrMoreTimes();

    Process::preventStrayProcesses();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs Pint when not present', function (): void {
    // Mock composer.json with all packages EXCEPT laravel/pint
    $packages = mockComposerJsonWithAllPackages();
    unset($packages['require-dev']['laravel/pint']);

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($packages));

    // Mock File::isDirectory calls
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists for all config files
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            // Return false for pint.json to trigger installation
            if ($path === base_path('pint.json')) {
                return false;
            }

            // Return true for other config files to avoid creating them
            return true;
        });

    // Mock reading existing config files
    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    File::shouldReceive('get')
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    // Expect pint.json to be created
    File::shouldReceive('put')
        ->once()
        ->with(
            base_path('pint.json'),
            Mockery::type('string')
        );

    // Allow composer.json to be updated with scripts
    File::shouldReceive('put')
        ->with(base_path('composer.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    // Mock .gitignore content

    File::shouldReceive('get')

        ->with(base_path('.gitignore'))

        ->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('append')

        ->with(base_path('.gitignore'), Mockery::type('string'))

        ->zeroOrMoreTimes();

    Process::preventStrayProcesses();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        'composer require --dev laravel/pint --with-all-dependencies' => Process::result(),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    // Verify that composer install process ran for Pint
    Process::assertRan('composer require --dev laravel/pint --with-all-dependencies');
});

it('creates pint.json when it does not exist', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            // Return false for pint.json to trigger creation
            if ($path === base_path('pint.json')) {
                return false;
            }

            // Return true for other config files
            return true;
        });

    // Mock reading existing config files
    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    File::shouldReceive('get')
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    File::shouldReceive('put')
        ->once()
        ->withArgs(function ($path, $content) {
            $config = json_decode($content, true);

            return $path === base_path('pint.json')
                && $config['preset'] === 'laravel'
                && isset($config['rules']['declare_strict_types'])
                && $config['rules']['declare_strict_types'] === true;
        });

    // Allow composer.json to be updated with scripts
    File::shouldReceive('put')
        ->with(base_path('composer.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    // Mock .gitignore content

    File::shouldReceive('get')

        ->with(base_path('.gitignore'))

        ->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('append')

        ->with(base_path('.gitignore'), Mockery::type('string'))

        ->zeroOrMoreTimes();

    Process::preventStrayProcesses();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('merges rules without overriding existing ones', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    File::shouldReceive('exists')
        ->andReturnUsing(fn ($path) =>
            // All config files exist
            true);

    $existingConfig = [
        'preset' => 'psr12',
        'rules' => [
            'declare_strict_types' => false, // User has this set to false
            'custom_rule' => true,
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('pint.json'))
        ->andReturn(json_encode($existingConfig));

    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    File::shouldReceive('get')
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    File::shouldReceive('put')
        ->once()
        ->withArgs(function ($path, $content) {
            $config = json_decode($content, true);

            return $path === base_path('pint.json')
                && $config['preset'] === 'psr12' // Keeps existing preset
                && $config['rules']['declare_strict_types'] === false // Keeps existing rule
                && $config['rules']['custom_rule'] === true // Keeps custom rule
                && isset($config['rules']['fully_qualified_strict_types']) // Adds missing rule
                && $config['rules']['fully_qualified_strict_types'] === true;
        });

    // Allow composer.json to be updated with scripts
    File::shouldReceive('put')
        ->with(base_path('composer.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    // Mock .gitignore content

    File::shouldReceive('get')

        ->with(base_path('.gitignore'))

        ->andReturn(".env\n.phpunit.cache\n");

    File::shouldReceive('append')

        ->with(base_path('.gitignore'), Mockery::type('string'))

        ->zeroOrMoreTimes();

    Process::preventStrayProcesses();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});
