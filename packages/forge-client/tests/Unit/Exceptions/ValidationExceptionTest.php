<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Exceptions\ForgeException;
use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

test('validation exception extends forge exception', function (): void {
    $exception = new ValidationException('Invalid parameter');

    expect($exception)->toBeInstanceOf(ForgeException::class);
});

test('can create validation exception for invalid parameter', function (): void {
    $exception = ValidationException::invalidParameter('size', '10gb', 'Must be a valid server size');

    expect($exception->getMessage())->toContain('Invalid parameter')
        ->and($exception->getMessage())->toContain('size')
        ->and($exception->getMessage())->toContain('10gb')
        ->and($exception->getContext())->toHaveKey('parameter')
        ->and($exception->getContext())->toHaveKey('value')
        ->and($exception->getContext())->toHaveKey('reason');
});

test('can create validation exception for missing parameter', function (): void {
    $exception = ValidationException::missingParameter('api_token');

    expect($exception->getMessage())->toContain('Missing required parameter')
        ->and($exception->getMessage())->toContain('api_token')
        ->and($exception->getContext()['parameter'])->toBe('api_token');
});

test('can create validation exception for invalid enum value', function (): void {
    $exception = ValidationException::invalidEnum('provider', 'invalid_provider', ['digitalocean', 'aws', 'linode']);

    expect($exception->getMessage())->toContain('Invalid value')
        ->and($exception->getMessage())->toContain('provider')
        ->and($exception->getMessage())->toContain('invalid_provider')
        ->and($exception->getContext()['allowed_values'])->toBe(['digitalocean', 'aws', 'linode']);
});

test('formats helpful error messages for invalid parameters', function (): void {
    $exception = ValidationException::invalidParameter('php_version', 'php90', 'PHP version must be between php81 and php84');

    $message = $exception->getMessage();

    expect($message)->toContain('Invalid parameter \'php_version\'')
        ->and($message)->toContain('\'php90\'')
        ->and($message)->toContain('PHP version must be between php81 and php84');
});
