<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Exceptions\ForgeException;

test('forge exception can be thrown with message', function (): void {
    expect(fn () => throw new ForgeException('Test error'))
        ->toThrow(ForgeException::class, 'Test error');
});

test('forge exception extends base exception', function (): void {
    $exception = new ForgeException('Test');

    expect($exception)->toBeInstanceOf(Exception::class);
});
