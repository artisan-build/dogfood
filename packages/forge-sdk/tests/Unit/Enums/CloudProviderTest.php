<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\CloudProvider;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('cloud provider has all expected cases', function (): void {
    $cases = CloudProvider::cases();

    expect($cases)->toHaveCount(7)
        ->and(CloudProvider::OCEAN->value)->toBe('ocean')
        ->and(CloudProvider::LINODE->value)->toBe('linode')
        ->and(CloudProvider::AWS->value)->toBe('aws')
        ->and(CloudProvider::VULTR->value)->toBe('vultr')
        ->and(CloudProvider::HETZNER->value)->toBe('hetzner')
        ->and(CloudProvider::LARAVEL->value)->toBe('laravel')
        ->and(CloudProvider::CUSTOM->value)->toBe('custom');
});

test('cloud provider validate succeeds for valid values', function (string $value): void {
    $provider = CloudProvider::validate($value);

    expect($provider)->toBeInstanceOf(CloudProvider::class)
        ->and($provider->value)->toBe($value);
})->with([
    'ocean',
    'linode',
    'aws',
    'vultr',
    'hetzner',
    'laravel',
    'custom',
]);

test('cloud provider validate throws exception for invalid value', function (): void {
    expect(fn () => CloudProvider::validate('invalid'))
        ->toThrow(ValidationException::class, 'Invalid cloud provider: invalid');
});

test('cloud provider tryFrom returns null for invalid value', function (): void {
    $result = CloudProvider::tryFrom('invalid');

    expect($result)->toBeNull();
});

test('cloud provider tryFrom returns enum for valid value', function (): void {
    $result = CloudProvider::tryFrom('ocean');

    expect($result)->toBe(CloudProvider::OCEAN);
});
