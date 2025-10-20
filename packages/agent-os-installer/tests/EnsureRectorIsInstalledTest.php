<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Rector is already installed', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists - all config files exist including rector.php
    File::shouldReceive('exists')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock pint.json content
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

it('installs Rector when not present', function (): void {
    // Mock composer.json with all packages EXCEPT rector/rector and driftingly/rector-laravel
    $packages = mockComposerJsonWithAllPackages();
    unset($packages['require-dev']['rector/rector']);
    unset($packages['require-dev']['driftingly/rector-laravel']);

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($packages));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists - only rector.php doesn't exist
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            if ($path === base_path('rector.php')) {
                return false;
            }

            return true;
        });

    // Mock existing config files
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

    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    // Expect rector.php to be created
    File::shouldReceive('put')
        ->with(base_path('rector.php'), Mockery::type('string'))
        ->once();

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
        'composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies' => Process::result(),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies');
});

it('creates rector.php when it does not exist', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists - pint.json and phpstan.neon exist, rector.php doesn't
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            if ($path === base_path('rector.php')) {
                return false;
            }

            return true;
        });

    // Mock existing config file reads
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

    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    // Expect rector.php to be created
    File::shouldReceive('put')
        ->once()
        ->withArgs(fn ($path, $content) => $path === base_path('rector.php')
            && str_contains((string) $content, 'RectorConfig::configure()')
            && str_contains((string) $content, 'LaravelLevelSetList::UP_TO_LARAVEL_120'));

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

it('preserves existing rector.php configuration', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists - all config files exist
    File::shouldReceive('exists')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock config file reads
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

    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn('level: 5');

    File::shouldReceive('get')
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    // Should NOT create rector.php since it already exists
    File::shouldReceive('put')
        ->with(base_path('rector.php'), Mockery::any())
        ->never();

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
