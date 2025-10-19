<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Exceptions\ForgeException;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('validation exception extends forge exception', function (): void {
    $exception = new ValidationException('Test message');

    expect($exception)->toBeInstanceOf(ForgeException::class);
});

test('validation exception can be thrown with message', function (): void {
    expect(fn () => throw new ValidationException('Invalid value'))
        ->toThrow(ValidationException::class, 'Invalid value');
});

test('validation exception can be thrown with message and code', function (): void {
    $exception = new ValidationException('Invalid value', 422);

    expect($exception)
        ->getMessage()->toBe('Invalid value')
        ->getCode()->toBe(422);
});
