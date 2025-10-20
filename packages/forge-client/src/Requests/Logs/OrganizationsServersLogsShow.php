<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Logs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.logs.show
 *
 *
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersLogsShow extends Request
{
    protected Method $method = Method::GET;

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
