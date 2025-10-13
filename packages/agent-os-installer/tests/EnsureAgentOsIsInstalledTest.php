<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Agent OS with Laravel profile is already installed', function (): void {
    $homeDir = $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? getenv('HOME') ?: getenv('USERPROFILE') ?: '~';

    // Mock Agent OS with Laravel profile exists
    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os/profiles/laravel')
        ->andReturn(true);

    // Mock File::isDirectory for general calls
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock other file operations for subsequent checks
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

it('installs Agent OS when not present', function (): void {
    $homeDir = $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? getenv('HOME') ?: getenv('USERPROFILE') ?: '~';

    // Mock Agent OS not installed
    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os/profiles/laravel')
        ->andReturn(false);

    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os')
        ->andReturn(false);

    // Allow general isDirectory calls
    File::shouldReceive('isDirectory')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock composer.json reads for subsequent checks
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [],
        ]));

    // Mock File::exists for config files
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            // Return false for config files to trigger creation
            if ($path === base_path('pint.json') ||
                $path === base_path('phpstan.neon') ||
                $path === base_path('rector.php')) {
                return false;
            }

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
        'gh repo clone artisan-build/agent-os agent-os' => Process::result(),
        'composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev laravel/pint --with-all-dependencies' => Process::result(),
        'composer require --dev larastan/larastan --with-all-dependencies' => Process::result(),
        'composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev tightenco/duster --with-all-dependencies' => Process::result(),
        'composer require --dev barryvdh/laravel-debugbar barryvdh/laravel-ide-helper --with-all-dependencies' => Process::result(),
    ]);

    // Mock user confirmation
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to install Agent OS now?', 'yes')
        ->assertExitCode(0);

    // Verify that the gh clone command was run
    Process::assertRan('gh repo clone artisan-build/agent-os agent-os');
});

it('installs Laravel profile when Agent OS exists but profile is missing', function (): void {
    $homeDir = $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? getenv('HOME') ?: getenv('USERPROFILE') ?: '~';

    // Mock Agent OS installed but Laravel profile missing
    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os/profiles/laravel')
        ->andReturn(false);

    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os')
        ->andReturn(true);

    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os/profiles')
        ->andReturn(true);

    // Mock isDirectory for temp path and other paths
    File::shouldReceive('isDirectory')
        ->andReturnUsing(function ($path) {
            // Return true for source path check in installLaravelProfile
            if (str_contains($path, '/agent-os-temp-') && str_contains($path, '/profiles/laravel')) {
                return true;
            }

            return true;
        });

    // Mock composer.json reads for subsequent checks
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [],
        ]));

    // Mock File::exists for config files
    File::shouldReceive('exists')
        ->andReturnUsing(function ($path) {
            // Return false for config files to trigger creation
            if ($path === base_path('pint.json') ||
                $path === base_path('phpstan.neon') ||
                $path === base_path('rector.php')) {
                return false;
            }

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

    File::shouldReceive('deleteDirectory')
        ->zeroOrMoreTimes();

    // Mock all process commands including gh clone for profile
    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        'gh repo clone artisan-build/agent-os *' => Process::result(),
        'composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev laravel/pint --with-all-dependencies' => Process::result(),
        'composer require --dev larastan/larastan --with-all-dependencies' => Process::result(),
        'composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev tightenco/duster --with-all-dependencies' => Process::result(),
        'composer require --dev barryvdh/laravel-debugbar barryvdh/laravel-ide-helper --with-all-dependencies' => Process::result(),
    ]);

    // Mock user confirmation
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to install the Laravel profile now?', 'yes')
        ->assertExitCode(0);
});
