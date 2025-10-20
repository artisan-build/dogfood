<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Integrations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.integrations.inertia.show
 *
 * Show whether Inertia SSR integration is enabled.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersSitesIntegrationsInertiaShow extends Request
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
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/integrations/inertia";
    }
}
