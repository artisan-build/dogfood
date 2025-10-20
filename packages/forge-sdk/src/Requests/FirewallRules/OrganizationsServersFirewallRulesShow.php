<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\FirewallRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.firewall-rules.show
 *
 * Get a specific firewall rule associated with the server.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersFirewallRulesShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $rule  The rule ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $rule,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/firewall-rules/{$this->rule}";
    }
}
