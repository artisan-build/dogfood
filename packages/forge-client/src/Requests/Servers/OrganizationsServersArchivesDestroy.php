<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.archives.destroy
 *
 * Unarchive a server.
 * Make sure you have regenerated the server key and added it to the server before
 * unarchiving.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersArchivesDestroy extends Request
{
    protected Method $method = Method::DELETE;

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
        return "/orgs/{$this->organization}/servers/archives/{$this->server}";
    }
}
