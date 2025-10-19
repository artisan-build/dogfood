<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * Cloud provider types supported by Laravel Forge.
 *
 * Verified against: /docs/api-reference/servers/create-server
 */
enum CloudProvider: string
{
    case OCEAN = 'ocean';
    case LINODE = 'linode';
    case AWS = 'aws';
    case VULTR = 'vultr';
    case HETZNER = 'hetzner';
    case LARAVEL = 'laravel';
    case CUSTOM = 'custom';

    /**
     * Validate a cloud provider value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid cloud provider
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid cloud provider: {$value}. Valid values are: ocean, linode, aws, vultr, hetzner, laravel, custom");
    }
}
