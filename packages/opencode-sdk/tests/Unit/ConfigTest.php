<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;

test('config file returns an array', function (): void {
    $config = include __DIR__.'/../../config/opencode-sdk.php';

    expect($config)->toBeArray();
});

test('config has expected structure with all required keys', function (): void {
    $config = config('opencode-sdk');

    expect($config)
        ->toHaveKeys(['base_url', 'timeout', 'auth', 'retry'])
        ->and($config['auth'])->toHaveKeys(['type', 'token'])
        ->and($config['retry'])->toHaveKeys(['times', 'sleep']);
});

test('config base_url has correct default value', function (): void {
    $config = config('opencode-sdk');

    expect($config['base_url'])->toBe('http://localhost:3333');
});

test('config timeout has correct default value', function (): void {
    $config = config('opencode-sdk');

    expect($config['timeout'])->toBe(30);
});

test('config auth type has correct default value', function (): void {
    $config = config('opencode-sdk');

    expect($config['auth']['type'])->toBeNull();
});

test('config auth token has correct default value', function (): void {
    $config = config('opencode-sdk');

    expect($config['auth']['token'])->toBeNull();
});

test('config retry times has correct default value', function (): void {
    $config = config('opencode-sdk');

    expect($config['retry']['times'])->toBe(3);
});

test('config retry sleep has correct default value', function (): void {
    $config = config('opencode-sdk');

    expect($config['retry']['sleep'])->toBe(100);
});

test('config base_url can be overridden by environment variable', function (): void {
    Config::set('opencode-sdk.base_url', 'https://custom-api.example.com');

    $config = config('opencode-sdk');

    expect($config['base_url'])->toBe('https://custom-api.example.com');
});

test('config timeout can be overridden by environment variable', function (): void {
    Config::set('opencode-sdk.timeout', 60);

    $config = config('opencode-sdk');

    expect($config['timeout'])->toBe(60);
});

test('config auth type can be overridden', function (): void {
    Config::set('opencode-sdk.auth.type', 'bearer');

    $config = config('opencode-sdk');

    expect($config['auth']['type'])->toBe('bearer');
});

test('config auth token can be overridden', function (): void {
    Config::set('opencode-sdk.auth.token', 'test-token-12345');

    $config = config('opencode-sdk');

    expect($config['auth']['token'])->toBe('test-token-12345');
});

test('config retry times can be overridden', function (): void {
    Config::set('opencode-sdk.retry.times', 5);

    $config = config('opencode-sdk');

    expect($config['retry']['times'])->toBe(5);
});

test('config retry sleep can be overridden', function (): void {
    Config::set('opencode-sdk.retry.sleep', 200);

    $config = config('opencode-sdk');

    expect($config['retry']['sleep'])->toBe(200);
});
