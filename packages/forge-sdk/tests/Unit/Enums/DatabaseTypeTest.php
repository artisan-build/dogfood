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

test('database type label returns human-readable names', function (): void {
    expect(DatabaseType::MYSQL->label())->toBe('MySQL 5.7')
        ->and(DatabaseType::MYSQL8->label())->toBe('MySQL 8.0')
        ->and(DatabaseType::POSTGRES->label())->toBe('PostgreSQL')
        ->and(DatabaseType::MARIADB->label())->toBe('MariaDB');
});

test('database type description returns detailed information', function (): void {
    expect(DatabaseType::MYSQL->description())->toContain('MySQL 5.7')
        ->and(DatabaseType::POSTGRES->description())->toContain('PostgreSQL')
        ->and(DatabaseType::MARIADB->description())->toContain('MariaDB');
});

test('database type isMySql returns correct values', function (): void {
    expect(DatabaseType::MYSQL->isMySql())->toBeTrue()
        ->and(DatabaseType::MYSQL8->isMySql())->toBeTrue()
        ->and(DatabaseType::MARIADB->isMySql())->toBeTrue()
        ->and(DatabaseType::POSTGRES->isMySql())->toBeFalse();
});

test('database type isPostgres returns correct values', function (): void {
    expect(DatabaseType::POSTGRES->isPostgres())->toBeTrue()
        ->and(DatabaseType::MYSQL->isPostgres())->toBeFalse()
        ->and(DatabaseType::MYSQL8->isPostgres())->toBeFalse()
        ->and(DatabaseType::MARIADB->isPostgres())->toBeFalse();
});

test('database type defaultPort returns correct values', function (): void {
    expect(DatabaseType::MYSQL->defaultPort())->toBe(3306)
        ->and(DatabaseType::MYSQL8->defaultPort())->toBe(3306)
        ->and(DatabaseType::MARIADB->defaultPort())->toBe(3306)
        ->and(DatabaseType::POSTGRES->defaultPort())->toBe(5432);
});

test('database type values returns array of all database type values', function (): void {
    $values = DatabaseType::values();

    expect($values)->toBeArray()
        ->toHaveCount(4)
        ->toContain('mysql', 'mysql8', 'postgres', 'mariadb');
});
