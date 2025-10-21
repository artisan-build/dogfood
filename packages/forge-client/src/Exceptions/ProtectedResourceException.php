<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Exceptions;

/**
 * Exception thrown when attempting to delete a protected resource.
 *
 * Protected resources are defined in the forge-client configuration file
 * and cannot be deleted via the SDK to prevent accidental deletion.
 */
class ProtectedResourceException extends ForgeException
{
    /**
     * Create a new exception for a protected site.
     */
    public static function site(int $siteId): static
    {
        return static::make(
            "Cannot delete protected site (ID: {$siteId}). This site is protected in the forge-client configuration.",
            [
                'resource_type' => 'site',
                'resource_id' => $siteId,
            ]
        );
    }

    /**
     * Create a new exception for a protected server.
     */
    public static function server(int $serverId): static
    {
        return static::make(
            "Cannot delete protected server (ID: {$serverId}). This server is protected in the forge-client configuration.",
            [
                'resource_type' => 'server',
                'resource_id' => $serverId,
            ]
        );
    }
}
