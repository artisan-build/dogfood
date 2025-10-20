<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Databases;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.database.password.update
 *
 * Update the password for the database on the server.
 * It will only update the password Forge thinks is
 * the password, it will not change the password on the server itself.
 * This should only be used if you
 * have changed the password on the server itself and want to update Forge.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersDatabasePasswordUpdate extends Request
{
    protected Method $method = Method::PUT;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/database/password";
    }
}
