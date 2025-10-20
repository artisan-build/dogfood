<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.heartbeats.show
 *
 * Show a specific heartbeat for the site.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersSitesHeartbeatsShow extends Request
{
    protected Method $method = Method::GET;

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
