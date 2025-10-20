<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Request;

it('detects when Agent OS with Laravel profile is already installed', function (): void {
    $homeDir = (Request::server('HOME') ?? Request::server('USERPROFILE') ?? getenv('HOME') ?: getenv('USERPROFILE')) ?: '~';

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
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

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

    File::shouldReceive('get')
        ->with(base_path('.gitignore'))
        ->andReturn(".env\n.phpunit.cache\n");

    // Allow composer.json to be updated with scripts
    File::shouldReceive('put')
        ->with(base_path('composer.json'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('append')
        ->with(base_path('.gitignore'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

    $this->artisan('agent-os:install')
        ->assertSuccessful();
});

it('installs Agent OS when not present', function (): void {
    $homeDir = (Request::server('HOME') ?? Request::server('USERPROFILE') ?? getenv('HOME') ?: getenv('USERPROFILE')) ?: '~';

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
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    File::shouldReceive('get')
        ->with(base_path('.gitignore'))
        ->andReturn(".env\n.phpunit.cache\n");

    // Mock File::exists - all config files exist
    File::shouldReceive('exists')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock config file content
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

    File::shouldReceive('append')
        ->with(base_path('.gitignore'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    Process::preventStrayProcesses();

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        'gh repo clone artisan-build/agent-os agent-os' => Process::result(),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    // Mock user confirmation
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to install Agent OS now?', 'yes')
        ->assertExitCode(0);

    // Verify that the gh clone command was run
    Process::assertRan('gh repo clone artisan-build/agent-os agent-os');
});

it('installs Laravel profile when Agent OS exists but profile is missing', function (): void {
    $homeDir = (Request::server('HOME') ?? Request::server('USERPROFILE') ?? getenv('HOME') ?: getenv('USERPROFILE')) ?: '~';

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
        ->andReturn(json_encode(mockComposerJsonWithAllPackages()));

    File::shouldReceive('get')
        ->with(base_path('.gitignore'))
        ->andReturn(".env\n.phpunit.cache\n");

    // Mock File::exists - all config files exist
    File::shouldReceive('exists')
        ->andReturn(true)
        ->zeroOrMoreTimes();

    // Mock config file content
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

    File::shouldReceive('append')
        ->with(base_path('.gitignore'), Mockery::type('string'))
        ->zeroOrMoreTimes();

    File::shouldReceive('makeDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('copyDirectory')
        ->zeroOrMoreTimes();

    File::shouldReceive('deleteDirectory')
        ->zeroOrMoreTimes();

    Process::preventStrayProcesses();

    // Mock all process commands including gh clone for profile
    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        'gh repo clone artisan-build/agent-os *' => Process::result(),
        '*/agent-os/scripts/project-install.sh*' => Process::result(),
    ]);

    // Mock user confirmation
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to install the Laravel profile now?', 'yes')
        ->assertExitCode(0);
});
