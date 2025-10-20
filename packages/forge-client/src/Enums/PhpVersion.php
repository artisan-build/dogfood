<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Enums;

use ArtisanBuild\ForgeClient\Exceptions\ValidationException;

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

    /**
     * Get an array of all PHP version values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get only currently supported PHP versions.
     *
     * @return array<self>
     */
    public static function supported(): array
    {
        return array_filter(
            self::cases(),
            fn (self $version) => $version->hasSecuritySupport()
        );
    }

    /**
     * Get a human-readable label for the PHP version.
     */
    public function label(): string
    {
        return match ($this) {
            self::PHP74 => 'PHP 7.4',
            self::PHP80 => 'PHP 8.0',
            self::PHP81 => 'PHP 8.1',
            self::PHP82 => 'PHP 8.2',
            self::PHP83 => 'PHP 8.3',
            self::PHP84 => 'PHP 8.4',
            self::PHP85 => 'PHP 8.5',
        };
    }

    /**
     * Get the numeric version string (e.g., "8.3").
     */
    public function numericVersion(): string
    {
        return match ($this) {
            self::PHP74 => '7.4',
            self::PHP80 => '8.0',
            self::PHP81 => '8.1',
            self::PHP82 => '8.2',
            self::PHP83 => '8.3',
            self::PHP84 => '8.4',
            self::PHP85 => '8.5',
        };
    }

    /**
     * Check if this PHP version is actively supported by the PHP project.
     */
    public function isActivelySupported(): bool
    {
        return match ($this) {
            self::PHP82, self::PHP83, self::PHP84 => true,
            default => false,
        };
    }

    /**
     * Check if this PHP version has security support.
     */
    public function hasSecuritySupport(): bool
    {
        return match ($this) {
            self::PHP81, self::PHP82, self::PHP83, self::PHP84, self::PHP85 => true,
            default => false,
        };
    }

    /**
     * Check if this is a legacy PHP version (no longer supported).
     */
    public function isLegacy(): bool
    {
        return match ($this) {
            self::PHP74, self::PHP80 => true,
            default => false,
        };
    }
}
