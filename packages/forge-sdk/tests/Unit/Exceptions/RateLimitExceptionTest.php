<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Exceptions\ApiException;
use ArtisanBuild\ForgeSdk\Exceptions\RateLimitException;

test('rate limit exception extends api exception', function (): void {
    $exception = new RateLimitException('Rate limit exceeded');

    expect($exception)->toBeInstanceOf(ApiException::class);
});

test('can create rate limit exception with retry information', function (): void {
    $exception = RateLimitException::fromRetryAfter(
        retryAfterSeconds: 60,
        endpoint: '/api/v1/servers',
        method: 'GET'
    );

    expect($exception->getRetryAfterSeconds())->toBe(60)
        ->and($exception->getStatusCode())->toBe(429);
});

test('calculates retry timestamp from retry-after seconds', function (): void {
    $now = time();
    $exception = RateLimitException::fromRetryAfter(
        retryAfterSeconds: 120,
        endpoint: '/api/v1/servers',
        method: 'POST'
    );

    $retryAt = $exception->getRetryAt();

    expect($retryAt)->toBeGreaterThanOrEqual($now + 120)
        ->and($retryAt)->toBeLessThanOrEqual($now + 121);
});

test('formats helpful message with retry information', function (): void {
    $exception = RateLimitException::fromRetryAfter(
        retryAfterSeconds: 60,
        endpoint: '/api/v1/servers',
        method: 'GET'
    );

    expect($exception->getMessage())->toContain('Rate limit exceeded')
        ->and($exception->getMessage())->toContain('60 seconds')
        ->and($exception->getMessage())->toContain('/api/v1/servers');
});

test('handles missing retry-after header gracefully', function (): void {
    $exception = RateLimitException::fromRetryAfter(
        retryAfterSeconds: null,
        endpoint: '/api/v1/servers',
        method: 'GET'
    );

    expect($exception->getRetryAfterSeconds())->toBeNull()
        ->and($exception->getMessage())->toContain('Rate limit exceeded');
});

test('provides method to check if retry is allowed', function (): void {
    $exception = RateLimitException::fromRetryAfter(
        retryAfterSeconds: -5, // Past time
        endpoint: '/api/v1/servers',
        method: 'GET'
    );

    expect($exception->canRetryNow())->toBeTrue();
});

test('indicates retry not allowed when within retry window', function (): void {
    $exception = RateLimitException::fromRetryAfter(
        retryAfterSeconds: 60,
        endpoint: '/api/v1/servers',
        method: 'GET'
    );

    expect($exception->canRetryNow())->toBeFalse();
});
