<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

beforeEach(function (): void {
    // Mock all the tool checks to pass
    Process::fake();
});

it('detects when all scripts are properly defined', function (): void {
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
                'barryvdh/laravel-ide-helper' => '^3.0',
            ],
            'scripts' => [
                'test' => [
                    '@php artisan config:clear --ansi',
                    '@php artisan test',
                ],
                'test-parallel' => [
                    '@php artisan config:clear --ansi',
                    '@php artisan test --parallel --recreate-databases',
                ],
                'lint' => [
                    'vendor/bin/duster fix',
                ],
                'rector' => [
                    'vendor/bin/rector',
                ],
                'stan' => [
                    'vendor/bin/phpstan analyse --memory-limit=512M',
                ],
                'ready' => [
                    '@php artisan config:clear --ansi',
                    '@php artisan ide-helper:models --write',
                    'composer rector',
                    'composer lint',
                    'composer stan',
                    'composer test',
                ],
                'report' => [
                    '@php artisan config:clear --ansi || true',
                    '@php artisan ide-helper:models --write || true',
                    'composer rector || true',
                    'composer lint || true',
                    'composer stan || true',
                    'composer test || true',
                ],
                'coverage-html' => [
                    'XDEBUG_MODE=coverage herd debug ./vendor/bin/pest --coverage-php coverage.php',
                    '@php artisan generate-code-coverage-html',
                ],
                'coverage' => [
                    'XDEBUG_MODE=coverage herd debug ./vendor/bin/pest --coverage',
                ],
                'types' => [
                    'vendor/bin/pest --type-coverage',
                ],
            ],
        ]));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('adds missing scripts', function (): void {
    $composerJson = [
        'require-dev' => [
            'pestphp/pest' => '^3.0',
            'laravel/pint' => '^1.0',
            'larastan/larastan' => '^3.0',
            'rector/rector' => '^2.0',
            'tightenco/duster' => '^3.0',
            'barryvdh/laravel-debugbar' => '^3.0',
            'barryvdh/laravel-ide-helper' => '^3.0',
        ],
        'scripts' => [
            'test' => [
                '@php artisan config:clear --ansi',
                '@php artisan test',
            ],
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($composerJson));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');

    File::shouldReceive('put')
        ->once()
        ->withArgs(function ($path, $content) {
            $updated = json_decode($content, true);

            return $path === base_path('composer.json')
                && isset($updated['scripts']['lint'])
                && isset($updated['scripts']['rector'])
                && isset($updated['scripts']['stan'])
                && isset($updated['scripts']['ready']);
        });

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('prompts for confirmation when scripts conflict', function (): void {
    $composerJson = [
        'require-dev' => [
            'pestphp/pest' => '^3.0',
            'laravel/pint' => '^1.0',
            'larastan/larastan' => '^3.0',
            'rector/rector' => '^2.0',
            'tightenco/duster' => '^3.0',
            'barryvdh/laravel-debugbar' => '^3.0',
            'barryvdh/laravel-ide-helper' => '^3.0',
        ],
        'scripts' => [
            'test' => [
                'phpunit', // Different from what we need
            ],
            'lint' => [
                'php-cs-fixer fix', // Different from what we need
            ],
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($composerJson));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');

    // Simulate user declining to overwrite
    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to overwrite these scripts with Agent OS optimized versions?', 'no')
        ->assertFailed();
});

it('overwrites scripts when user confirms', function (): void {
    $composerJson = [
        'require-dev' => [
            'pestphp/pest' => '^3.0',
            'laravel/pint' => '^1.0',
            'larastan/larastan' => '^3.0',
            'rector/rector' => '^2.0',
            'tightenco/duster' => '^3.0',
            'barryvdh/laravel-debugbar' => '^3.0',
            'barryvdh/laravel-ide-helper' => '^3.0',
        ],
        'scripts' => [
            'test' => [
                'phpunit',
            ],
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode($composerJson));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');

    File::shouldReceive('put')
        ->once()
        ->withArgs(function ($path, $content) {
            $updated = json_decode($content, true);

            return $path === base_path('composer.json')
                && $updated['scripts']['test'] === [
                    '@php artisan config:clear --ansi',
                    '@php artisan test',
                ];
        });

    $this->artisan('agent-os:install')
        ->expectsConfirmation('Would you like to overwrite these scripts with Agent OS optimized versions?', 'yes')
        ->assertSuccessful();
});
