<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.php.versions.show
 *
 * Show the PHP version which has been installed on the server
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersPhpVersionsShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $phpVersion,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/php/versions/{$this->phpVersion}";
    }
}
