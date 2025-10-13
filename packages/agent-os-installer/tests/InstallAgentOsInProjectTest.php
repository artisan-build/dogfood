<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('installs Agent OS in the project', function () {
    $homeDir = $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? '~';

    // Mock GitHub CLI check
    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

    // Mock Agent OS with Laravel profile exists
    File::shouldReceive('isDirectory')
        ->with($homeDir.'/agent-os/profiles/laravel')
        ->andReturn(true);

    // Mock composer.json reads for all subsequent checks
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

    // Mock the project install script execution
    Process::fake([
        $homeDir.'/agent-os/scripts/project-install.sh --multi-agent-mode true --single-agent-mode true --profile laravel' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});
