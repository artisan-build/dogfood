<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Providers\ForgeClientServiceProvider;
use Illuminate\Support\Facades\Config;

test('service provider is registered', function (): void {
    $provider = app()->getProvider(ForgeClientServiceProvider::class);

    expect($provider)->not->toBeNull()
        ->and($provider)->toBeInstanceOf(ForgeClientServiceProvider::class);
});

test('forge sdk connector is registered as singleton', function (): void {
    $first = app(ForgeClient::class);
    $second = app(ForgeClient::class);

    expect($first)->toBeInstanceOf(ForgeClient::class)
        ->and($first)->toBe($second);
});

test('forge sdk resolves with api token from config', function (): void {
    Config::set('forge-sdk.api_token', 'test-api-token');

    $sdk = app(ForgeClient::class);

    expect($sdk)->toBeInstanceOf(ForgeClient::class);
    expect(config('forge-sdk.api_token'))->toBe('test-api-token');
});

test('config file can be published', function (): void {
    $configPath = config_path('forge-sdk.php');

    if (file_exists($configPath)) {
        unlink($configPath);
    }

    $this->artisan('vendor:publish', [
        '--tag' => 'forge-sdk-config',
        '--force' => true,
    ])->assertSuccessful();

    expect(file_exists($configPath))->toBeTrue();

    // Clean up
    if (file_exists($configPath)) {
        unlink($configPath);
    }
});
