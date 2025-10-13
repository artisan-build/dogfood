<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when development tools are already installed', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'pestphp/pest' => '^3.0',
                'laravel/pint' => '^1.0',
                'larastan/larastan' => '^3.0',
                'rector/rector' => '^2.0',
                'tightenco/duster' => '^3.0',
                'barryvdh/laravel-debugbar' => '^3.0',
                'barryvdh/laravel-ide-helper' => '^2.0',
            ],
        ]));

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

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs missing development tools', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [],
        ]));

    // Mock File::isDirectory calls (from EnsureAgentOsIsInstalled)
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock File::exists for config files - return false to trigger installation
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            // Return false for config files to trigger creation
            if ($path === base_path('pint.json') ||
                $path === base_path('phpstan.neon') ||
                $path === base_path('rector.php')) {
                return false;
            }

            // Return true for other paths
            return true;
        });

    // Expect config files to be created
    File::shouldReceive('put')
        ->with(base_path('pint.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('put')
        ->with(base_path('phpstan.neon'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('put')
        ->with(base_path('rector.php'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    // Allow composer.json to be updated with scripts
    File::shouldReceive('put')
        ->with(base_path('composer.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        'composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev laravel/pint --with-all-dependencies' => Process::result(),
        'composer require --dev larastan/larastan --with-all-dependencies' => Process::result(),
        'composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev tightenco/duster --with-all-dependencies' => Process::result(),
        'composer require --dev barryvdh/laravel-debugbar barryvdh/laravel-ide-helper --with-all-dependencies' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev barryvdh/laravel-debugbar barryvdh/laravel-ide-helper --with-all-dependencies');
});

it('installs only missing development tools', function (): void {
    // Mock composer.json - will be called multiple times by different actions
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'pestphp/pest' => '^3.0',
                'laravel/pint' => '^1.0',
                'larastan/larastan' => '^3.0',
                'rector/rector' => '^2.0',
                'tightenco/duster' => '^3.0',
                'barryvdh/laravel-debugbar' => '^3.0',
            ],
        ]));

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

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        'composer require --dev barryvdh/laravel-ide-helper --with-all-dependencies' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev barryvdh/laravel-ide-helper --with-all-dependencies');
});
