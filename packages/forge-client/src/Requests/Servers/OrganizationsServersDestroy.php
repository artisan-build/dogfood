<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use ArtisanBuild\ForgeClient\Exceptions\ProtectedResourceException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.destroy
 *
 * Delete a server.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
    ) {
        $this->checkProtection();
    }

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}";
    }

    /**
     * Check if the server is protected from deletion.
     *
     * @throws ProtectedResourceException
     */
    protected function checkProtection(): void
    {
        $protectedServers = config('forge-client.protected_servers', []);

        if (in_array($this->server, $protectedServers, true)) {
            throw ProtectedResourceException::server($this->server);
        }
    }
}
