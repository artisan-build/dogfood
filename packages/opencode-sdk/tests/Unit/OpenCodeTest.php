<?php

declare(strict_types=1);

use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use ArtisanBuild\OpencodeSdk\OpenCode\Resource\Misc;

test('connector can be instantiated', function (): void {
    $connector = new OpenCode;

    expect($connector)->toBeInstanceOf(OpenCode::class);
});

test('connector has default base URL', function (): void {
    $connector = new OpenCode;

    expect($connector->resolveBaseUrl())->toBe('http://localhost:3333');
});

test('connector accepts custom base URL', function (): void {
    $connector = new OpenCode(baseUrl: 'https://custom-api.example.com');

    expect($connector->resolveBaseUrl())->toBe('https://custom-api.example.com');
});

test('connector provides misc resource', function (): void {
    $connector = new OpenCode;

    expect($connector->misc())->toBeInstanceOf(Misc::class);
});

test('connector config can store values', function (): void {
    $connector = new OpenCode;
    $connector->config()->add('timeout', 60);

    expect($connector->config()->get('timeout'))->toBe(60);
});
