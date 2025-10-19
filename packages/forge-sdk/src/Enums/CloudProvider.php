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

    /**
     * Get an array of all provider values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get a human-readable label for the cloud provider.
     */
    public function label(): string
    {
        return match ($this) {
            self::OCEAN => 'DigitalOcean',
            self::LINODE => 'Linode (Akamai)',
            self::AWS => 'Amazon Web Services',
            self::VULTR => 'Vultr',
            self::HETZNER => 'Hetzner Cloud',
            self::LARAVEL => 'Laravel Cloud',
            self::CUSTOM => 'Custom VPS',
        };
    }

    /**
     * Get a description of the cloud provider.
     */
    public function description(): string
    {
        return match ($this) {
            self::OCEAN => 'DigitalOcean is a popular cloud infrastructure provider with data centers worldwide.',
            self::LINODE => 'Linode (now part of Akamai) offers reliable cloud computing services.',
            self::AWS => 'Amazon Web Services is the world\'s most comprehensive cloud platform.',
            self::VULTR => 'Vultr provides high-performance SSD cloud servers.',
            self::HETZNER => 'Hetzner Cloud offers cost-effective cloud hosting with European data centers.',
            self::LARAVEL => 'Laravel Cloud is the official Laravel hosting platform.',
            self::CUSTOM => 'Connect your own custom VPS or dedicated server.',
        };
    }

    /**
     * Check if this provider requires server credentials.
     */
    public function requiresCredentials(): bool
    {
        return $this !== self::CUSTOM;
    }

    /**
     * Check if this provider supports VPC/private networking.
     */
    public function supportsVpc(): bool
    {
        return match ($this) {
            self::OCEAN, self::AWS, self::HETZNER, self::VULTR, self::LARAVEL => true,
            self::LINODE, self::CUSTOM => false,
        };
    }
}
