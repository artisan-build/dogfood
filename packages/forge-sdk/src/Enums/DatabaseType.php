<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

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
}
