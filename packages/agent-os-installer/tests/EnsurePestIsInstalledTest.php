<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Pest is already installed', function (): void {
    File::shouldReceive('get')
        ->once()
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'pestphp/pest' => '^3.0',
                'pestphp/pest-plugin-laravel' => '^3.0',
            ],
        ]));

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('detects when Pest plugin is installed without main package', function (): void {
    File::shouldReceive('get')
        ->once()
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'pestphp/pest-plugin-laravel' => '^3.0',
            ],
        ]));

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs Pest when not present', function (): void {
    File::shouldReceive('get')
        ->once()
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [],
        ]));

    Process::fake([
        'composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies');
});
