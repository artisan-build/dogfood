<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Exceptions\ApiException;
use ArtisanBuild\ForgeSdk\Exceptions\AuthenticationException;

test('authentication exception extends api exception', function (): void {
    $exception = new AuthenticationException('Unauthenticated');

    expect($exception)->toBeInstanceOf(ApiException::class);
});

test('can create authentication exception for 401 response', function (): void {
    $exception = AuthenticationException::unauthenticated(
        endpoint: '/api/v1/servers',
        method: 'GET'
    );

    expect($exception->getStatusCode())->toBe(401)
        ->and($exception->getMessage())->toContain('Authentication failed')
        ->and($exception->getMessage())->toContain('API token');
});

test('can create authentication exception for 403 response', function (): void {
    $exception = AuthenticationException::forbidden(
        endpoint: '/api/v1/servers/123',
        method: 'DELETE',
        message: 'You do not have permission to delete this server'
    );

    expect($exception->getStatusCode())->toBe(403)
        ->and($exception->getMessage())->toContain('Forbidden')
        ->and($exception->getMessage())->toContain('You do not have permission to delete this server');
});

test('provides helpful troubleshooting suggestions for 401', function (): void {
    $exception = AuthenticationException::unauthenticated('/api/v1/servers', 'GET');

    expect($exception->getMessage())->toContain('FORGE_API_TOKEN')
        ->and($exception->getMessage())->toContain('valid')
        ->and($exception->getMessage())->toContain('expired');
});

test('includes endpoint information in error message', function (): void {
    $exception = AuthenticationException::unauthenticated('/api/v1/servers/123', 'DELETE');

    expect($exception->getMessage())->toContain('/api/v1/servers/123')
        ->and($exception->getMessage())->toContain('DELETE');
});

test('formats custom forbidden message when provided', function (): void {
    $customMessage = 'Organization access denied';
    $exception = AuthenticationException::forbidden('/api/v1/organizations/5', 'GET', $customMessage);

    expect($exception->getMessage())->toContain($customMessage);
});
