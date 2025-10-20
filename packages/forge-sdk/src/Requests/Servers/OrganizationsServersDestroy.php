<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.destroy
 *
 * Delete a server.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersDestroy extends Request
{
    protected Method $method = Method::DELETE;

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
        return "/orgs/{$this->organization}/servers/{$this->server}";
    }
}
