<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Enums;

use ArtisanBuild\ForgeSdk\Exceptions\ValidationException;

/**
 * Server types available in Laravel Forge.
 *
 * Verified against: /docs/api-reference/servers/create-server
 */
enum ServerType: string
{
    case APP = 'app';
    case WEB = 'web';
    case LOADBALANCER = 'loadbalancer';
    case DATABASE = 'database';
    case CACHE = 'cache';
    case WORKER = 'worker';
    case MEILISEARCH = 'meilisearch';

    /**
     * Validate a server type value and return the enum case.
     *
     * @throws ValidationException If the value is not a valid server type
     */
    public static function validate(string $value): self
    {
        return self::tryFrom($value)
            ?? throw new ValidationException("Invalid server type: {$value}. Valid values are: app, web, loadbalancer, database, cache, worker, meilisearch");
    }

    /**
     * Get an array of all server type values.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Get a human-readable label for the server type.
     */
    public function label(): string
    {
        return match ($this) {
            self::APP => 'Application Server',
            self::WEB => 'Web Server',
            self::LOADBALANCER => 'Load Balancer',
            self::DATABASE => 'Database Server',
            self::CACHE => 'Cache Server',
            self::WORKER => 'Worker Server',
            self::MEILISEARCH => 'Meilisearch Server',
        };
    }

    /**
     * Get a description of the server type.
     */
    public function description(): string
    {
        return match ($this) {
            self::APP => 'Full-stack application server with PHP, database, and web server.',
            self::WEB => 'Web server only (nginx/apache) without PHP or database.',
            self::LOADBALANCER => 'Load balancer to distribute traffic across multiple servers.',
            self::DATABASE => 'Dedicated database server (MySQL/PostgreSQL).',
            self::CACHE => 'Dedicated cache server (Redis/Memcached).',
            self::WORKER => 'Background worker server for queue processing.',
            self::MEILISEARCH => 'Dedicated Meilisearch server for full-text search.',
        };
    }

    /**
     * Check if this server type includes PHP.
     */
    public function includesPhp(): bool
    {
        return match ($this) {
            self::APP, self::WORKER => true,
            default => false,
        };
    }

    /**
     * Check if this server type includes a database.
     */
    public function includesDatabase(): bool
    {
        return match ($this) {
            self::APP, self::DATABASE => true,
            default => false,
        };
    }

    /**
     * Check if this server type can host websites.
     */
    public function canHostSites(): bool
    {
        return match ($this) {
            self::APP, self::WEB => true,
            default => false,
        };
    }
}
