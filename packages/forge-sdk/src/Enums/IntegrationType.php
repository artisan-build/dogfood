<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * Integration types (source control providers) supported by Laravel Forge.
 *
 * Verified against: /docs/api-reference/integrations (Create Integration endpoint)
 */
enum IntegrationType: string
{
    case GITHUB = 'github';
    case GITLAB = 'gitlab';
    case BITBUCKET = 'bitbucket';
    case CUSTOM = 'custom';

    /**
     * Validate an integration type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid integration type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid integration type: {$value}. Valid values are: github, gitlab, bitbucket, custom");
    }
}
