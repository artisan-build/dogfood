<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * Log types available in Laravel Forge.
 *
 * Verified against: /docs/api-reference/logs
 */
enum LogType: string
{
    case NGINX_ACCESS = 'nginx_access';
    case NGINX_ERROR = 'nginx_error';
    case PHP = 'php';
    case DATABASE = 'database';

    /**
     * Validate a log type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid log type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid log type: {$value}. Valid values are: nginx_access, nginx_error, php, database");
    }
}
