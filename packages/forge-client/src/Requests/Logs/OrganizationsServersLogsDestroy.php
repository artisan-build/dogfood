<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Logs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.logs.destroy
 *
 *
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersLogsDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected string $key,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/logs/{$this->key}";
    }
}
