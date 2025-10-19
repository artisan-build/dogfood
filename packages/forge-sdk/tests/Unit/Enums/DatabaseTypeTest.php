<?php

declare(strict_types=1);

use ArtisanBuild\ForgeSdk\Enums\DatabaseType;
use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

test('database type has all expected cases', function (): void {
    $cases = DatabaseType::cases();

    expect($cases)->toHaveCount(4)
        ->and(DatabaseType::MYSQL->value)->toBe('mysql')
        ->and(DatabaseType::MYSQL8->value)->toBe('mysql8')
        ->and(DatabaseType::POSTGRES->value)->toBe('postgres')
        ->and(DatabaseType::MARIADB->value)->toBe('mariadb');
});

test('database type validate succeeds for valid values', function (string $value): void {
    $type = DatabaseType::validate($value);

    expect($type)->toBeInstanceOf(DatabaseType::class)
        ->and($type->value)->toBe($value);
})->with([
    'mysql',
    'mysql8',
    'postgres',
    'mariadb',
]);

test('database type validate throws exception for invalid value', function (): void {
    expect(fn () => DatabaseType::validate('mongodb'))
        ->toThrow(ValidationException::class, 'Invalid database type: mongodb');
});

test('database type tryFrom returns null for invalid value', function (): void {
    $result = DatabaseType::tryFrom('mongodb');

    expect($result)->toBeNull();
});
