<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Databases;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.database.schemas.show
 *
 * Get a specific database schema associated with the server.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersDatabaseSchemasShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $database  The database ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $database,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/database/schemas/{$this->database}";
    }
}
