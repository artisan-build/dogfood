<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Databases;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.database.users.show
 *
 * Get a specific database user associated with the server.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersDatabaseUsersShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $databaseUser  The database user ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $databaseUser,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/database/users/{$this->databaseUser}";
    }
}
