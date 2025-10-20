<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Enums;

use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

/**
 * Site types available when creating sites in Laravel Forge.
 *
 * Verified against: /docs/api-reference/sites/create-site
 */
enum SiteType: string
{
    case LARAVEL = 'laravel';
    case SYMFONY = 'symfony';
    case STATAMIC = 'statamic';
    case WORDPRESS = 'wordpress';
    case PHPMYADMIN = 'phpmyadmin';
    case PHP = 'php';
    case NEXT_JS = 'next.js';
    case NUXT_JS = 'nuxt.js';
    case STATIC_HTML = 'static-html';
    case OTHER = 'other';
    case CUSTOM = 'custom';

    /**
     * Validate a site type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid site type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid site type: {$value}. Valid values are: laravel, symfony, statamic, wordpress, phpmyadmin, php, next.js, nuxt.js, static-html, other, custom");
    }
}
