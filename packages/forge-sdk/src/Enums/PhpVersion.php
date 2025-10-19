<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * PHP versions available in Laravel Forge.
 *
 * Verified against: /docs/api-reference/sites (Change PHP Version endpoint)
 */
enum PhpVersion: string
{
    case PHP74 = 'php74';
    case PHP80 = 'php80';
    case PHP81 = 'php81';
    case PHP82 = 'php82';
    case PHP83 = 'php83';
    case PHP84 = 'php84';
    case PHP85 = 'php85';

    /**
     * Validate a PHP version value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid PHP version
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid PHP version: {$value}. Valid values are: php74, php80, php81, php82, php83, php84, php85");
    }
}
