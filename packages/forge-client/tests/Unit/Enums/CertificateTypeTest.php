<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Enums\CertificateType;
use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

test('certificate type has all expected cases', function (): void {
    $cases = CertificateType::cases();

    expect($cases)->toHaveCount(3)
        ->and(CertificateType::LETSENCRYPT->value)->toBe('letsencrypt')
        ->and(CertificateType::EXISTING->value)->toBe('existing')
        ->and(CertificateType::CLONE->value)->toBe('clone');
});

test('certificate type validate succeeds for valid values', function (string $value): void {
    $type = CertificateType::validate($value);

    expect($type)->toBeInstanceOf(CertificateType::class)
        ->and($type->value)->toBe($value);
})->with([
    'letsencrypt',
    'existing',
    'clone',
]);

test('certificate type validate throws exception for invalid value', function (): void {
    expect(fn () => CertificateType::validate('selfsigned'))
        ->toThrow(ValidationException::class, 'Invalid certificate type: selfsigned');
});

test('certificate type tryFrom returns null for invalid value', function (): void {
    $result = CertificateType::tryFrom('selfsigned');

    expect($result)->toBeNull();
});
