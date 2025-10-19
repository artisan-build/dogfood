<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Deployments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.webhooks.destroy
 *
 * Delete a specific webhook from the site.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersSitesWebhooksDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $deploymentWebhook  The deployment webhook ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected int $deploymentWebhook,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/webhooks/{$this->deploymentWebhook}";
    }
}
