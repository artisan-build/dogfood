<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Deployments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.deployments.log.show
 *
 *
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersSitesDeploymentsLogShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $deployment  The deployment ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected int $deployment,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/deployments/{$this->deployment}/log";
    }
}
