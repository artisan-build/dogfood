<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Console\Concerns;

use ArtisanBuild\ForgeClient\ForgeClient;
use InvalidArgumentException;

/**
 * Provides methods to resolve resource identifiers (name or ID) to IDs.
 *
 * Allows commands to accept either a numeric ID or a friendly name,
 * resolving names to IDs by fetching the resource list and matching.
 */
trait ResolvesResourceIdentifiers
{
    /**
     * Resolve a server identifier (name or ID) to a server ID.
     */
    protected function resolveServerId(string|int $identifier, string $organization, ForgeClient $forge): int
    {
        // If numeric or int, assume it's an ID
        if (is_int($identifier) || is_numeric($identifier)) {
            return (int) $identifier;
        }

        // Otherwise, resolve by name
        $response = $forge->servers()->organizationsServersIndex(
            organization: $organization,
            sort: null,
            pagesize: null,
            pagecursor: null,
            filteripAddress: null,
            filtername: $identifier,
            filterregion: null,
            filtersize: null,
            filterprovider: null,
            filterubuntuVersion: null,
            filterphpVersion: null,
            filterdatabaseType: null
        );

        if (! $response->successful()) {
            throw new InvalidArgumentException("Unable to fetch servers: {$response->body()}");
        }

        $data = $response->json();
        $servers = collect($data['data'] ?? []);

        // Filter for exact name match (JSON:API format - attributes.name)
        $matches = $servers->filter(fn ($server) => ($server['attributes']['name'] ?? null) === $identifier);

        if ($matches->isEmpty()) {
            throw new InvalidArgumentException("Server '{$identifier}' not found in organization '{$organization}'");
        }

        if ($matches->count() > 1) {
            $ids = $matches->pluck('id')->implode(', ');

            throw new InvalidArgumentException(
                "Multiple servers found with name '{$identifier}'. Please use ID instead: {$ids}"
            );
        }

        return (int) $matches->first()['id'];
    }

    /**
     * Resolve an organization identifier (slug or ID) to an organization slug.
     */
    protected function resolveOrganizationSlug(string|int $identifier, ForgeClient $forge): string
    {
        // Convert to string for consistency
        $identifier = (string) $identifier;

        // If it looks like a slug (contains letters), return as-is
        if (preg_match('/[a-zA-Z]/', $identifier)) {
            return $identifier;
        }

        // If numeric, fetch all organizations and find by ID
        if (is_numeric($identifier)) {
            $response = $forge->organizations()->organizationsIndex(pagesize: null, pagecursor: null);

            if (! $response->successful()) {
                throw new InvalidArgumentException("Unable to fetch organizations: {$response->body()}");
            }

            $data = $response->json();
            $organizations = collect($data['data'] ?? []);

            $match = $organizations->firstWhere('id', (string) $identifier);

            if (! $match) {
                throw new InvalidArgumentException("Organization with ID '{$identifier}' not found");
            }

            return $match['attributes']['slug'] ?? $match['slug'];
        }

        return $identifier;
    }

    /**
     * Resolve a server identifier (name or ID) to a server ID.
     */
    protected function resolveServerIdentifier(string|int $identifier, string $organization, ForgeClient $forge): int
    {
        return $this->resolveServerId($identifier, $organization, $forge);
    }

    /**
     * Resolve a site identifier (name or ID) to a site ID.
     */
    protected function resolveSiteIdentifier(string|int $identifier, string $organization, string|int $server, ForgeClient $forge): int
    {
        // If numeric or int, assume it's an ID
        if (is_int($identifier) || is_numeric($identifier)) {
            return (int) $identifier;
        }

        // Otherwise, resolve by name
        $response = $forge->sites()->organizationsServersSitesIndex(
            organization: $organization,
            server: $server,
            sort: null,
            include: null,
            pagesize: null,
            pagecursor: null,
            filtername: $identifier,
        );

        if (! $response->successful()) {
            throw new InvalidArgumentException("Unable to fetch sites: {$response->body()}");
        }

        $data = $response->json();
        $sites = collect($data['data'] ?? []);

        // Filter for exact name match (JSON:API format - attributes.name)
        $matches = $sites->filter(fn ($site) => ($site['attributes']['name'] ?? null) === $identifier);

        if ($matches->isEmpty()) {
            throw new InvalidArgumentException("Site '{$identifier}' not found on server '{$server}'");
        }

        if ($matches->count() > 1) {
            $ids = $matches->pluck('id')->implode(', ');

            throw new InvalidArgumentException(
                "Multiple sites found with name '{$identifier}'. Please use ID instead: {$ids}"
            );
        }

        return (int) $matches->first()['id'];
    }
}
