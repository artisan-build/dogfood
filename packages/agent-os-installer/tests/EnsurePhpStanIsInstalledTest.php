<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when PHPStan is already installed', function (): void {
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
        ->andReturn("parameters:\n    level: 6");

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

it('installs PHPStan when not present', function (): void {
    // Mock composer.json with all packages EXCEPT larastan/larastan
    $packages = mockComposerJsonWithAllPackages();
    unset($packages['require-dev']['larastan/larastan']);

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($packages));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists - only phpstan.neon doesn't exist
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            if ($path === base_path('phpstan.neon')) {
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
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    // Expect phpstan.neon to be created
    File::shouldReceive('put')
        ->with(base_path('phpstan.neon'), Mockery::type('string'))
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
        'composer require --dev larastan/larastan --with-all-dependencies' => Process::result(),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev larastan/larastan --with-all-dependencies');
});

it('creates phpstan.neon when it does not exist', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists - pint.json and rector.php exist, phpstan.neon doesn't
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            if ($path === base_path('phpstan.neon')) {
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
        ->with(base_path('rector.php'))
        ->andReturn('<?php');

    // Expect phpstan.neon to be created
    File::shouldReceive('put')
        ->once()
        ->withArgs(fn ($path, $content) => $path === base_path('phpstan.neon')
            && str_contains((string) $content, 'level: 6'));

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

it('warns when PHPStan level is below recommended', function (): void {
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

    // Mock phpstan.neon content with low level
    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn("parameters:\n    level: 3");

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

    Artisan::call('agent-os:install');

    $output = Artisan::output();

    expect($output)->toContain('PHPStan level is currently set to 3')
        ->and($output)->toContain('recommend level 5 or higher');
});

it('does not warn when PHPStan level is at or above recommended', function (): void {
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

    // Mock phpstan.neon content with good level
    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn("parameters:\n    level: 6");

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

    Artisan::call('agent-os:install');

    $output = Artisan::output();

    expect($output)->not->toContain('PHPStan level is currently set to')
        ->and($output)->toContain('phpstan.neon configuration is acceptable');
});
