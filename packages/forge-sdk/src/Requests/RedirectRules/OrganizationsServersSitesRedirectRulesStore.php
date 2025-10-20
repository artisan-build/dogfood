<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\RedirectRules;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.servers.sites.redirect-rules.store
 *
 * Add a new redirect rule to the site.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersSitesRedirectRulesStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

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
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/redirect-rules";
    }
}
