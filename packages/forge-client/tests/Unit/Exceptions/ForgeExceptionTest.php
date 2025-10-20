<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Exceptions\ForgeException;

test('forge exception can be thrown with message', function (): void {
    expect(fn () => throw new ForgeException('Test error'))
        ->toThrow(ForgeException::class, 'Test error');
});

test('forge exception extends base exception', function (): void {
    $exception = new ForgeException('Test');

    expect($exception)->toBeInstanceOf(Exception::class);
});

test('can be created with context data using make factory', function (): void {
    $context = [
        'endpoint' => '/api/v1/servers',
        'method' => 'POST',
    ];

    $exception = ForgeException::make('API request failed', $context);

    expect($exception->getMessage())->toBe('API request failed')
        ->and($exception->getContext())->toBe($context);
});

test('context defaults to empty array', function (): void {
    $exception = new ForgeException('Test error');

    expect($exception->getContext())->toBe([]);
});

test('can add context after instantiation', function (): void {
    $exception = new ForgeException('Test error');
    $exception->addContext('key', 'value');

    expect($exception->getContext())->toBe(['key' => 'value']);
});

test('can merge multiple context values', function (): void {
    $exception = ForgeException::make('Test error', ['initial' => 'value']);
    $exception->addContext('added', 'data');

    expect($exception->getContext())->toBe([
        'initial' => 'value',
        'added' => 'data',
    ]);
});
