<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when PHPStan is already installed', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->with(base_path('phpstan.neon'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn("parameters:\n    level: 6");

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs PHPStan when not present', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [],
        ]), json_encode([
            'require-dev' => [],
        ]), json_encode([
            'require-dev' => [],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true, true, false);

    File::shouldReceive('put')
        ->once()
        ->with(
            base_path('phpstan.neon'),
            Mockery::type('string')
        );

    Process::fake([
        'composer require --dev larastan/larastan --with-all-dependencies' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev larastan/larastan --with-all-dependencies');
});

it('creates phpstan.neon when it does not exist', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true, true, false);

    File::shouldReceive('put')
        ->once()
        ->withArgs(fn ($path, $content) => $path === base_path('phpstan.neon')
            && str_contains((string) $content, 'level: 6'));

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('warns when PHPStan level is below recommended', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true, true, true);

    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn("parameters:\n    level: 3");

    Process::fake();

    Artisan::call('agent-os:install');

    $output = Artisan::output();

    expect($output)->toContain('PHPStan level is currently set to 3')
        ->and($output)->toContain('recommend level 5 or higher');
});

it('does not warn when PHPStan level is at or above recommended', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'larastan/larastan' => '^3.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true, true, true);

    File::shouldReceive('get')
        ->with(base_path('phpstan.neon'))
        ->andReturn("parameters:\n    level: 6");

    Process::fake();

    Artisan::call('agent-os:install');

    $output = Artisan::output();

    expect($output)->not->toContain('PHPStan level is currently set to')
        ->and($output)->toContain('phpstan.neon configuration is acceptable');
});
