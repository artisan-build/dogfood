<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\RedirectRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.redirect-rules.destroy
 *
 * Remove a redirect rule from the site.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersSitesRedirectRulesDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $redirectRule  The redirect rule ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected int $redirectRule,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/redirect-rules/{$this->redirectRule}";
    }
}
