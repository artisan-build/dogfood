<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\JobFrequency;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('job frequency has all expected cases', function (): void {
    $cases = JobFrequency::cases();

    expect($cases)->toHaveCount(7)
        ->and(JobFrequency::MINUTELY->value)->toBe('minutely')
        ->and(JobFrequency::HOURLY->value)->toBe('hourly')
        ->and(JobFrequency::NIGHTLY->value)->toBe('nightly')
        ->and(JobFrequency::WEEKLY->value)->toBe('weekly')
        ->and(JobFrequency::MONTHLY->value)->toBe('monthly')
        ->and(JobFrequency::REBOOT->value)->toBe('reboot')
        ->and(JobFrequency::CUSTOM->value)->toBe('custom');
});

test('job frequency validate succeeds for valid values', function (string $value): void {
    $frequency = JobFrequency::validate($value);

    expect($frequency)->toBeInstanceOf(JobFrequency::class)
        ->and($frequency->value)->toBe($value);
})->with([
    'minutely',
    'hourly',
    'nightly',
    'weekly',
    'monthly',
    'reboot',
    'custom',
]);

test('job frequency validate throws exception for invalid value', function (): void {
    expect(fn () => JobFrequency::validate('daily'))
        ->toThrow(ValidationException::class, 'Invalid job frequency: daily');
});

test('job frequency tryFrom returns null for invalid value', function (): void {
    $result = JobFrequency::tryFrom('daily');

    expect($result)->toBeNull();
});
