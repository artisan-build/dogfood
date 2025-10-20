<?php

declare(strict_types=1);

use ArtisanBuild\ForgeClient\Enums\LogType;
use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

test('log type has all expected cases', function (): void {
    $cases = LogType::cases();

    expect($cases)->toHaveCount(4)
        ->and(LogType::NGINX_ACCESS->value)->toBe('nginx_access')
        ->and(LogType::NGINX_ERROR->value)->toBe('nginx_error')
        ->and(LogType::PHP->value)->toBe('php')
        ->and(LogType::DATABASE->value)->toBe('database');
});

test('log type validate succeeds for valid values', function (string $value): void {
    $type = LogType::validate($value);

    expect($type)->toBeInstanceOf(LogType::class)
        ->and($type->value)->toBe($value);
})->with([
    'nginx_access',
    'nginx_error',
    'php',
    'database',
]);

test('log type validate throws exception for invalid value', function (): void {
    expect(fn () => LogType::validate('system'))
        ->toThrow(ValidationException::class, 'Invalid log type: system');
});

test('log type tryFrom returns null for invalid value', function (): void {
    $result = LogType::tryFrom('system');

    expect($result)->toBeNull();
});
