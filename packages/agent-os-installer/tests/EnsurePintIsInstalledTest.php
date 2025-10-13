<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Pint is already installed', function () {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'laravel/pint' => '^1.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->with(base_path('pint.json'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(base_path('pint.json'))
        ->andReturn(json_encode([
            'preset' => 'laravel',
            'rules' => [
                'declare_strict_types' => true,
            ],
        ]));

    File::shouldReceive('put')
        ->never();

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs Pint when not present', function () {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [],
        ]), json_encode([
            'require-dev' => [],
        ]));

    File::shouldReceive('exists')
        ->with(base_path('pint.json'))
        ->andReturn(false);

    File::shouldReceive('put')
        ->once()
        ->with(
            base_path('pint.json'),
            Mockery::type('string')
        );

    Process::fake([
        'composer require --dev laravel/pint --with-all-dependencies' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev laravel/pint --with-all-dependencies');
});

it('creates pint.json when it does not exist', function () {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'laravel/pint' => '^1.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'laravel/pint' => '^1.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->with(base_path('pint.json'))
        ->andReturn(false);

    File::shouldReceive('put')
        ->once()
        ->withArgs(function ($path, $content) {
            $config = json_decode($content, true);

            return $path === base_path('pint.json')
                && $config['preset'] === 'laravel'
                && isset($config['rules']['declare_strict_types'])
                && $config['rules']['declare_strict_types'] === true;
        });

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('merges rules without overriding existing ones', function () {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'laravel/pint' => '^1.0',
            ],
        ]), json_encode([
            'require-dev' => [
                'laravel/pint' => '^1.0',
            ],
        ]));

    File::shouldReceive('exists')
        ->with(base_path('pint.json'))
        ->andReturn(true);

    $existingConfig = [
        'preset' => 'psr12',
        'rules' => [
            'declare_strict_types' => false, // User has this set to false
            'custom_rule' => true,
        ],
    ];

    File::shouldReceive('get')
        ->with(base_path('pint.json'))
        ->andReturn(json_encode($existingConfig));

    File::shouldReceive('put')
        ->once()
        ->withArgs(function ($path, $content) {
            $config = json_decode($content, true);

            return $path === base_path('pint.json')
                && $config['preset'] === 'psr12' // Keeps existing preset
                && $config['rules']['declare_strict_types'] === false // Keeps existing rule
                && $config['rules']['custom_rule'] === true // Keeps custom rule
                && isset($config['rules']['fully_qualified_strict_types']) // Adds missing rule
                && $config['rules']['fully_qualified_strict_types'] === true;
        });

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});
