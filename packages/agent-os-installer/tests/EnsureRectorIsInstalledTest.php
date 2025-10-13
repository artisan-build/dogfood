<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Rector is already installed', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->with(base_path('rector.php'))
        ->andReturn(true);

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs Rector when not present', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [],
        ]), json_encode([
            'require-dev' => [],
        ]), json_encode([
            'require-dev' => [],
        ]), json_encode([
            'require-dev' => [],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true, true, true, false);

    File::shouldReceive('put')
        ->once()
        ->with(
            base_path('rector.php'),
            Mockery::type('string')
        );

    Process::fake([
        'composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies');
});

it('creates rector.php when it does not exist', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true, true, true, false);

    File::shouldReceive('put')
        ->once()
        ->withArgs(fn ($path, $content) => $path === base_path('rector.php')
            && str_contains((string) $content, 'RectorConfig::configure()')
            && str_contains((string) $content, 'LaravelLevelSetList::UP_TO_LARAVEL_120'));

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('preserves existing rector.php configuration', function (): void {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'rector/rector' => '^2.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->andReturn(true, true, true, true);

    File::shouldReceive('put')
        ->never();

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});
