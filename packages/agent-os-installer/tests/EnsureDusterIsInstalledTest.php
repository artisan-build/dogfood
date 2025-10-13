<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

it('detects when Duster is already installed', function () {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(json_encode([
            'require-dev' => [
                'tightenco/duster' => '^3.0',
            ],
        ]));

    File::shouldReceive('exists')->andReturn(true);
    File::shouldReceive('get')->with(base_path('pint.json'))->andReturn('{}');
    File::shouldReceive('get')->with(base_path('phpstan.neon'))->andReturn('level: 6');

    Process::fake();

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);
});

it('installs Duster when not present', function () {
    File::shouldReceive('get')
        ->with(base_path('composer.json'))
        ->andReturn(
            json_encode(['require-dev' => []]),
            json_encode(['require-dev' => []]),
            json_encode(['require-dev' => []]),
            json_encode(['require-dev' => []]),
            json_encode(['require-dev' => []]),
            json_encode(['require-dev' => []])
        );

    File::shouldReceive('exists')->andReturn(true, true, true, true, false, false, false, false, false);
    File::shouldReceive('put')->times(4);

    Process::fake([
        'composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev laravel/pint --with-all-dependencies' => Process::result(),
        'composer require --dev larastan/larastan --with-all-dependencies' => Process::result(),
        'composer require --dev rector/rector driftingly/rector-laravel --with-all-dependencies' => Process::result(),
        'composer require --dev tightenco/duster --with-all-dependencies' => Process::result(),
        'composer require --dev barryvdh/laravel-debugbar barryvdh/laravel-ide-helper --with-all-dependencies' => Process::result(),
    ]);

    $exitCode = Artisan::call('agent-os:install');

    expect($exitCode)->toBe(0);

    Process::assertRan('composer require --dev tightenco/duster --with-all-dependencies');
});
