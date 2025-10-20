<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.php.site-version.update
 *
 * Update the PHP site version for the server.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersPhpSiteVersionUpdate extends Request
{
    protected Method $method = Method::PUT;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/php/site-version";
    }
}
