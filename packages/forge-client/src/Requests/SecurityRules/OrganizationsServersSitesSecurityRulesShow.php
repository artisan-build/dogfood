<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\SecurityRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.security-rules.show
 *
 * Get a specific security rule associated with the site.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersSitesSecurityRulesShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $securityRule  The security rule ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected int $securityRule,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/security-rules/{$this->securityRule}";
    }
}
