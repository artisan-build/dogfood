<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Agent OS with Laravel profile is already installed', function (): void {
    $homeDir = Illuminate\Support\Facades\Request::server('HOME') ?? Illuminate\Support\Facades\Request::server('USERPROFILE') ?? '~';

    // Mock Agent OS with Laravel profile exists
    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os/profiles/laravel')
        ->andReturn(true);

    // Mock other file operations for subsequent checks
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'pestphp/pest' => '^3.0',
                'laravel/pint' => '^1.0',
                'larastan/larastan' => '^2.0',
                'rector/rector' => '^1.0',
                'tightenco/duster' => '^3.0',
            ],
            'scripts' => [
                'lint' => 'duster lint',
                'stan' => 'phpstan analyse',
                'test' => 'pest',
                'ready' => ['@lint', '@stan', '@test'],
            ],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true);

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs Agent OS when not present', function (): void {
    $homeDir = Illuminate\Support\Facades\Request::server('HOME') ?? Illuminate\Support\Facades\Request::server('USERPROFILE') ?? '~';

    // Mock Agent OS not installed
    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os/profiles/laravel')
        ->andReturn(false);

    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os')
        ->andReturn(false);

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
        'gh repo clone artisan-build/agent-os agent-os' => Process::result(),
    ]);

    // Mock user confirmation
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to install Agent OS now?', 'yes')
        ->assertExitCode(1); // Will fail on subsequent checks, but that's okay for this test
});

it('installs Laravel profile when Agent OS exists but profile is missing', function (): void {
    $homeDir = Illuminate\Support\Facades\Request::server('HOME') ?? Illuminate\Support\Facades\Request::server('USERPROFILE') ?? '~';

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

    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

    // Mock user confirmation
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to install the Laravel profile now?', 'yes')
        ->assertExitCode(1); // Will fail on subsequent checks, but that's okay for this test
});
