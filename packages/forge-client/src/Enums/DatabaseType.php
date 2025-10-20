<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Enums;

use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

/**
 * Database types available in Laravel Forge.
 *
 * Verified against: /docs/api-reference/databases (Create Database endpoint)
 */
enum DatabaseType: string
{
    case MYSQL = 'mysql';
    case MYSQL8 = 'mysql8';
    case POSTGRES = 'postgres';
    case MARIADB = 'mariadb';

    /**
     * Validate a database type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid database type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid database type: {$value}. Valid values are: mysql, mysql8, postgres, mariadb");
    }

    /**
     * Get an array of all database type values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get a human-readable label for the database type.
     */
    public function label(): string
    {
        return match ($this) {
            self::MYSQL => 'MySQL 5.7',
            self::MYSQL8 => 'MySQL 8.0',
            self::POSTGRES => 'PostgreSQL',
            self::MARIADB => 'MariaDB',
        };
    }

    /**
     * Get a description of the database type.
     */
    public function description(): string
    {
        return match ($this) {
            self::MYSQL => 'MySQL 5.7 is a mature relational database (legacy version).',
            self::MYSQL8 => 'MySQL 8.0 is the modern version of the world\'s most popular open-source database.',
            self::POSTGRES => 'PostgreSQL is a powerful, enterprise-class open-source relational database.',
            self::MARIADB => 'MariaDB is a community-developed fork of MySQL with additional features.',
        };
    }

    /**
     * Check if this is a MySQL variant.
     */
    public function isMySql(): bool
    {
        return match ($this) {
            self::MYSQL, self::MYSQL8, self::MARIADB => true,
            default => false,
        };
    }

    /**
     * Check if this is PostgreSQL.
     */
    public function isPostgres(): bool
    {
        return $this === self::POSTGRES;
    }

    /**
     * Get the default port for this database type.
     */
    public function defaultPort(): int
    {
        return match ($this) {
            self::MYSQL, self::MYSQL8, self::MARIADB => 3306,
            self::POSTGRES => 5432,
        };
    }
}
