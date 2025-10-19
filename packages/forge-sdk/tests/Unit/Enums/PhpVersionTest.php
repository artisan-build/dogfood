<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\PhpVersion;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

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
