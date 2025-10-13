<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when GitHub CLI is installed', function (): void {
    // Mock GitHub CLI check
    Process::fake([
        'which gh' => Process::result(output: '/opt/homebrew/bin/gh'),
    ]);

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

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('which gh');
});

it('fails when GitHub CLI is not installed', function (): void {
    Process::fake([
        'which gh' => Process::result(exitCode: 1),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(1);

    Process::assertRan('which gh');
});
