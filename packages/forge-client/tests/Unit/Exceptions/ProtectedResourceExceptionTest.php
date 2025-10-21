<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Exceptions\ProtectedResourceException;

test('site exception creates proper message and context', function (): void {
    $exception = ProtectedResourceException::site(123);

    expect($exception->getMessage())
        ->toContain('Cannot delete protected site')
        ->toContain('123')
        ->and($exception->getContext())
        ->toBe([
            'resource_type' => 'site',
            'resource_id' => 123,
        ]);
});

test('server exception creates proper message and context', function (): void {
    $exception = ProtectedResourceException::server(456);

    expect($exception->getMessage())
        ->toContain('Cannot delete protected server')
        ->toContain('456')
        ->and($exception->getContext())
        ->toBe([
            'resource_type' => 'server',
            'resource_id' => 456,
        ]);
});
