<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.sites.show
 *
 * Show the specified site.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsSitesShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $site  The site ID
     */
    public function __construct(
        protected string $organization,
        protected int $site,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/sites/{$this->site}";
    }
}
