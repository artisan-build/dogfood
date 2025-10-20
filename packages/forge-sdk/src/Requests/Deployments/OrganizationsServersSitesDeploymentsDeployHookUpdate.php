<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Deployments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.deployments.deploy-hook.update
 *
 * Regenerate the deployment hook token used for triggering deployment on the deployment
 * URL.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersSitesDeploymentsDeployHookUpdate extends Request
{
    protected Method $method = Method::PUT;

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
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/deployments/deploy-hook";
    }
}
