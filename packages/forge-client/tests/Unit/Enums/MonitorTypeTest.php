<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Enums\MonitorType;
use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

test('monitor type has all expected cases', function (): void {
    $cases = MonitorType::cases();

    expect($cases)->toHaveCount(3)
        ->and(MonitorType::CPU_LOAD->value)->toBe('cpu_load')
        ->and(MonitorType::USED_DISK_SPACE->value)->toBe('used_disk_space')
        ->and(MonitorType::MEMORY_USAGE->value)->toBe('memory_usage');
});

test('monitor type validate succeeds for valid values', function (string $value): void {
    $type = MonitorType::validate($value);

    expect($type)->toBeInstanceOf(MonitorType::class)
        ->and($type->value)->toBe($value);
})->with([
    'cpu_load',
    'used_disk_space',
    'memory_usage',
]);

test('monitor type validate throws exception for invalid value', function (): void {
    expect(fn () => MonitorType::validate('network'))
        ->toThrow(ValidationException::class, 'Invalid monitor type: network');
});

test('monitor type tryFrom returns null for invalid value', function (): void {
    $result = MonitorType::tryFrom('network');

    expect($result)->toBeNull();
});
