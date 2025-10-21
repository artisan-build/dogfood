<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Sites;

use ArtisanBuild\ForgeClient\Exceptions\ProtectedResourceException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.destroy
 *
 * Remove a site from the server.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersSitesDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
    ) {
        $this->checkProtection();
    }

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}";
    }

    /**
     * Check if the site is protected from deletion.
     *
     * @throws ProtectedResourceException
     */
    protected function checkProtection(): void
    {
        $protectedSites = config('forge-client.protected_sites', []);

        if (in_array($this->site, $protectedSites, true)) {
            throw ProtectedResourceException::site($this->site);
        }
    }
}
