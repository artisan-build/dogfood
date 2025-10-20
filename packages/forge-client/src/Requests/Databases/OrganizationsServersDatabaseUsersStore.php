<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Databases;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.servers.database.users.store
 *
 * Add a new database user to the server.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersDatabaseUsersStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

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
        return "/orgs/{$this->organization}/servers/{$this->server}/database/users";
    }
}
