<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Enums;

use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

/**
 * SSL certificate types available in Laravel Forge.
 *
 * Verified against: /docs/api-reference/ssl-certificates
 */
enum CertificateType: string
{
    case LETSENCRYPT = 'letsencrypt';
    case EXISTING = 'existing';
    case CLONE = 'clone';

    /**
     * Validate a certificate type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid certificate type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid certificate type: {$value}. Valid values are: letsencrypt, existing, clone");
    }
}
