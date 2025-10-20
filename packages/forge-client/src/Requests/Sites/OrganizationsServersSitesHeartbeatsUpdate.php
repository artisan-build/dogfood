<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.heartbeats.update
 *
 * Update a specific heartbeat for the site.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersSitesHeartbeatsUpdate extends Request
{
    protected Method $method = Method::PUT;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $heartbeat  The heartbeat ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected int $heartbeat,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/heartbeats/{$this->heartbeat}";
    }
}
