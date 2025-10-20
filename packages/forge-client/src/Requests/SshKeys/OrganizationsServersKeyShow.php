<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\SshKeys;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.key.show
 *
 * Get the public SSH key for the server.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersKeyShow extends Request
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
        return "/orgs/{$this->organization}/servers/{$this->server}/key";
    }
}
