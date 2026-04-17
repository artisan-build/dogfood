<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use Illuminate\Support\Facades\Config;

test('service provider registers connector in container', function (): void {
    expect(app()->bound(OpenCode::class))->toBeTrue();
});

test('connector can be resolved from container', function (): void {
    $connector = resolve(OpenCode::class);

    expect($connector)->toBeInstanceOf(OpenCode::class);
});

test('connector is bound as singleton', function (): void {
    $connector1 = resolve(OpenCode::class);
    $connector2 = resolve(OpenCode::class);

    expect($connector1)->toBe($connector2);
});

test('connector receives base URL from config', function (): void {
    Config::set('opencode-sdk.base_url', 'https://test-api.example.com');

    $connector = resolve(OpenCode::class);

    expect($connector->resolveBaseUrl())->toBe('https://test-api.example.com');
});

test('connector uses default base URL when not configured', function (): void {
    $connector = resolve(OpenCode::class);
    $baseUrl = $connector->resolveBaseUrl();

    expect($baseUrl)->toBe('http://localhost:3333');
});
