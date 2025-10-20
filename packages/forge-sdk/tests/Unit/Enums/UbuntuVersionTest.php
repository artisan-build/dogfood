<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\UbuntuVersion;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('ubuntu version has all expected cases', function (): void {
    $cases = UbuntuVersion::cases();

    expect($cases)->toHaveCount(2)
        ->and(UbuntuVersion::UBUNTU_22_04->value)->toBe('22.04')
        ->and(UbuntuVersion::UBUNTU_24_04->value)->toBe('24.04');
});

test('ubuntu version validate succeeds for valid values', function (string $value): void {
    $version = UbuntuVersion::validate($value);

    expect($version)->toBeInstanceOf(UbuntuVersion::class)
        ->and($version->value)->toBe($value);
})->with([
    '22.04',
    '24.04',
]);

test('ubuntu version validate throws exception for invalid value', function (): void {
    expect(fn () => UbuntuVersion::validate('20.04'))
        ->toThrow(ValidationException::class, 'Invalid Ubuntu version: 20.04');
});

test('ubuntu version tryFrom returns null for invalid value', function (): void {
    $result = UbuntuVersion::tryFrom('20.04');

    expect($result)->toBeNull();
});

test('ubuntu version tryFrom returns enum for valid value', function (): void {
    $result = UbuntuVersion::tryFrom('22.04');

    expect($result)->toBe(UbuntuVersion::UBUNTU_22_04);
});

test('ubuntu version label returns human-readable names', function (): void {
    expect(UbuntuVersion::UBUNTU_22_04->label())->toBe('Ubuntu 22.04 LTS (Jammy Jellyfish)')
        ->and(UbuntuVersion::UBUNTU_24_04->label())->toBe('Ubuntu 24.04 LTS (Noble Numbat)');
});

test('ubuntu version shortLabel returns concise names', function (): void {
    expect(UbuntuVersion::UBUNTU_22_04->shortLabel())->toBe('Ubuntu 22.04 LTS')
        ->and(UbuntuVersion::UBUNTU_24_04->shortLabel())->toBe('Ubuntu 24.04 LTS');
});

test('ubuntu version codename returns correct codenames', function (): void {
    expect(UbuntuVersion::UBUNTU_22_04->codename())->toBe('Jammy Jellyfish')
        ->and(UbuntuVersion::UBUNTU_24_04->codename())->toBe('Noble Numbat');
});

test('ubuntu version isLts returns true for all versions', function (): void {
    expect(UbuntuVersion::UBUNTU_22_04->isLts())->toBeTrue()
        ->and(UbuntuVersion::UBUNTU_24_04->isLts())->toBeTrue();
});

test('ubuntu version endOfLifeDate returns correct dates', function (): void {
    expect(UbuntuVersion::UBUNTU_22_04->endOfLifeDate())->toBe('2027-04')
        ->and(UbuntuVersion::UBUNTU_24_04->endOfLifeDate())->toBe('2029-04');
});

test('ubuntu version isRecommended returns correct values', function (): void {
    expect(UbuntuVersion::UBUNTU_24_04->isRecommended())->toBeTrue()
        ->and(UbuntuVersion::UBUNTU_22_04->isRecommended())->toBeFalse();
});

test('ubuntu version values returns array of all version values', function (): void {
    $values = UbuntuVersion::values();

    expect($values)->toBeArray()
        ->toHaveCount(2)
        ->toContain('22.04', '24.04');
});

test('ubuntu version latest returns the newest version', function (): void {
    expect(UbuntuVersion::latest())->toBe(UbuntuVersion::UBUNTU_24_04);
});
