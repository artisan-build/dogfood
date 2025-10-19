<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * Ubuntu versions available in Laravel Forge.
 *
 * Verified against: /docs/api-reference/servers/create-server
 */
enum UbuntuVersion: string
{
    case UBUNTU_22_04 = '22.04';
    case UBUNTU_24_04 = '24.04';

    /**
     * Validate an Ubuntu version value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid Ubuntu version
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid Ubuntu version: {$value}. Valid values are: 22.04, 24.04");
    }

    /**
     * Get an array of all Ubuntu version values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get the latest/recommended Ubuntu version.
     */
    public static function latest(): self
    {
        return self::UBUNTU_24_04;
    }

    /**
     * Get a human-readable label for the Ubuntu version.
     */
    public function label(): string
    {
        return match ($this) {
            self::UBUNTU_22_04 => 'Ubuntu 22.04 LTS (Jammy Jellyfish)',
            self::UBUNTU_24_04 => 'Ubuntu 24.04 LTS (Noble Numbat)',
        };
    }

    /**
     * Get a short label for the Ubuntu version.
     */
    public function shortLabel(): string
    {
        return match ($this) {
            self::UBUNTU_22_04 => 'Ubuntu 22.04 LTS',
            self::UBUNTU_24_04 => 'Ubuntu 24.04 LTS',
        };
    }

    /**
     * Get the Ubuntu codename.
     */
    public function codename(): string
    {
        return match ($this) {
            self::UBUNTU_22_04 => 'Jammy Jellyfish',
            self::UBUNTU_24_04 => 'Noble Numbat',
        };
    }

    /**
     * Check if this is an LTS (Long Term Support) version.
     * Note: All versions currently supported by Forge are LTS.
     */
    public function isLts(): bool
    {
        return true;
    }

    /**
     * Get the end-of-life date for this Ubuntu version.
     */
    public function endOfLifeDate(): string
    {
        return match ($this) {
            self::UBUNTU_22_04 => '2027-04',
            self::UBUNTU_24_04 => '2029-04',
        };
    }

    /**
     * Check if this version is recommended for new servers.
     */
    public function isRecommended(): bool
    {
        return $this === self::UBUNTU_24_04;
    }
}
