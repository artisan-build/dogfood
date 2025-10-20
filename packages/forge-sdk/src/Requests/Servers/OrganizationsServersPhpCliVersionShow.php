<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.php.cli-version.show
 *
 * Show the PHP CLI version which has been set for the server.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersPhpCliVersionShow extends Request
{
    protected Method $method = Method::GET;

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
        return "/orgs/{$this->organization}/servers/{$this->server}/php/cli-version";
    }
}
