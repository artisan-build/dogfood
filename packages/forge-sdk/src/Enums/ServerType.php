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
}
