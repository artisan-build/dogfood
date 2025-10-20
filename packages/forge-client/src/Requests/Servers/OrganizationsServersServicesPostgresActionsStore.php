<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.servers.services.postgres.actions.store
 *
 * Run an action on the Postgres service.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersServicesPostgresActionsStore extends Request implements HasBody
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
        return "/orgs/{$this->organization}/servers/{$this->server}/services/postgres/actions";
    }
}
