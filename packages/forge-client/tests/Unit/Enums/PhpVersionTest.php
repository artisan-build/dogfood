<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Enums\PhpVersion;
use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

test('php version has all expected cases', function (): void {
    $cases = PhpVersion::cases();

    expect($cases)->toHaveCount(7)
        ->and(PhpVersion::PHP74->value)->toBe('php74')
        ->and(PhpVersion::PHP80->value)->toBe('php80')
        ->and(PhpVersion::PHP81->value)->toBe('php81')
        ->and(PhpVersion::PHP82->value)->toBe('php82')
        ->and(PhpVersion::PHP83->value)->toBe('php83')
        ->and(PhpVersion::PHP84->value)->toBe('php84')
        ->and(PhpVersion::PHP85->value)->toBe('php85');
});

test('php version validate succeeds for valid values', function (string $value): void {
    $version = PhpVersion::validate($value);

    expect($version)->toBeInstanceOf(PhpVersion::class)
        ->and($version->value)->toBe($value);
})->with([
    'php74',
    'php80',
    'php81',
    'php82',
    'php83',
    'php84',
    'php85',
]);

test('php version validate throws exception for invalid value', function (): void {
    expect(fn () => PhpVersion::validate('php99'))
        ->toThrow(ValidationException::class, 'Invalid PHP version: php99');
});

test('php version tryFrom returns null for invalid value', function (): void {
    $result = PhpVersion::tryFrom('php99');

    expect($result)->toBeNull();
});

test('php version tryFrom returns enum for valid value', function (): void {
    $result = PhpVersion::tryFrom('php83');

    expect($result)->toBe(PhpVersion::PHP83);
});

test('php version label returns human-readable names', function (): void {
    expect(PhpVersion::PHP74->label())->toBe('PHP 7.4')
        ->and(PhpVersion::PHP80->label())->toBe('PHP 8.0')
        ->and(PhpVersion::PHP81->label())->toBe('PHP 8.1')
        ->and(PhpVersion::PHP82->label())->toBe('PHP 8.2')
        ->and(PhpVersion::PHP83->label())->toBe('PHP 8.3')
        ->and(PhpVersion::PHP84->label())->toBe('PHP 8.4')
        ->and(PhpVersion::PHP85->label())->toBe('PHP 8.5');
});

test('php version numericVersion returns correct format', function (): void {
    expect(PhpVersion::PHP74->numericVersion())->toBe('7.4')
        ->and(PhpVersion::PHP83->numericVersion())->toBe('8.3')
        ->and(PhpVersion::PHP84->numericVersion())->toBe('8.4');
});

test('php version isActivelySupported returns correct values', function (): void {
    expect(PhpVersion::PHP82->isActivelySupported())->toBeTrue()
        ->and(PhpVersion::PHP83->isActivelySupported())->toBeTrue()
        ->and(PhpVersion::PHP84->isActivelySupported())->toBeTrue()
        ->and(PhpVersion::PHP74->isActivelySupported())->toBeFalse()
        ->and(PhpVersion::PHP80->isActivelySupported())->toBeFalse()
        ->and(PhpVersion::PHP81->isActivelySupported())->toBeFalse();
});

test('php version hasSecuritySupport returns correct values', function (): void {
    expect(PhpVersion::PHP81->hasSecuritySupport())->toBeTrue()
        ->and(PhpVersion::PHP82->hasSecuritySupport())->toBeTrue()
        ->and(PhpVersion::PHP83->hasSecuritySupport())->toBeTrue()
        ->and(PhpVersion::PHP74->hasSecuritySupport())->toBeFalse()
        ->and(PhpVersion::PHP80->hasSecuritySupport())->toBeFalse();
});

test('php version isLegacy returns correct values', function (): void {
    expect(PhpVersion::PHP74->isLegacy())->toBeTrue()
        ->and(PhpVersion::PHP80->isLegacy())->toBeTrue()
        ->and(PhpVersion::PHP81->isLegacy())->toBeFalse()
        ->and(PhpVersion::PHP82->isLegacy())->toBeFalse()
        ->and(PhpVersion::PHP83->isLegacy())->toBeFalse();
});

test('php version values returns array of all version values', function (): void {
    $values = PhpVersion::values();

    expect($values)->toBeArray()
        ->toHaveCount(7)
        ->toContain('php74', 'php80', 'php81', 'php82', 'php83', 'php84', 'php85');
});

test('php version supported returns only supported versions', function (): void {
    $supported = PhpVersion::supported();

    expect($supported)->toBeArray()
        ->and(array_values($supported))->toContain(PhpVersion::PHP81, PhpVersion::PHP82, PhpVersion::PHP83, PhpVersion::PHP84)
        ->and(array_values($supported))->not->toContain(PhpVersion::PHP74, PhpVersion::PHP80);
});
