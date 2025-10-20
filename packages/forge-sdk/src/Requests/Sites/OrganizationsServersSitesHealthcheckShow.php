<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.healthcheck.show
 *
 * Show the healthcheck endpoint that has been set for the site.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersSitesHealthcheckShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/healthcheck";
    }
}
