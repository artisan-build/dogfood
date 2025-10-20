<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Enums\CloudProvider;
use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

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

test('cloud provider label returns human-readable names', function (): void {
    expect(CloudProvider::OCEAN->label())->toBe('DigitalOcean')
        ->and(CloudProvider::LINODE->label())->toBe('Linode (Akamai)')
        ->and(CloudProvider::AWS->label())->toBe('Amazon Web Services')
        ->and(CloudProvider::VULTR->label())->toBe('Vultr')
        ->and(CloudProvider::HETZNER->label())->toBe('Hetzner Cloud')
        ->and(CloudProvider::LARAVEL->label())->toBe('Laravel Cloud')
        ->and(CloudProvider::CUSTOM->label())->toBe('Custom VPS');
});

test('cloud provider description returns detailed information', function (): void {
    expect(CloudProvider::OCEAN->description())->toContain('DigitalOcean')
        ->and(CloudProvider::AWS->description())->toContain('Amazon Web Services')
        ->and(CloudProvider::CUSTOM->description())->toContain('custom VPS');
});

test('cloud provider requiresCredentials returns correct values', function (): void {
    expect(CloudProvider::OCEAN->requiresCredentials())->toBeTrue()
        ->and(CloudProvider::AWS->requiresCredentials())->toBeTrue()
        ->and(CloudProvider::CUSTOM->requiresCredentials())->toBeFalse();
});

test('cloud provider supportsVpc returns correct values', function (): void {
    expect(CloudProvider::OCEAN->supportsVpc())->toBeTrue()
        ->and(CloudProvider::AWS->supportsVpc())->toBeTrue()
        ->and(CloudProvider::HETZNER->supportsVpc())->toBeTrue()
        ->and(CloudProvider::VULTR->supportsVpc())->toBeTrue()
        ->and(CloudProvider::LARAVEL->supportsVpc())->toBeTrue()
        ->and(CloudProvider::LINODE->supportsVpc())->toBeFalse()
        ->and(CloudProvider::CUSTOM->supportsVpc())->toBeFalse();
});

test('cloud provider values returns array of all provider values', function (): void {
    $values = CloudProvider::values();

    expect($values)->toBeArray()
        ->toHaveCount(7)
        ->toContain('ocean', 'linode', 'aws', 'vultr', 'hetzner', 'laravel', 'custom');
});
