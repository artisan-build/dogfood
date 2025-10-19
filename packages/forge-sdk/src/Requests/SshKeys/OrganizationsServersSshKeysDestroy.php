<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\SshKeys;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.ssh-keys.destroy
 *
 * Remove an SSH key from the server.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersSshKeysDestroy extends Request
{
    protected Method $method = Method::DELETE;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $key  The key ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $key,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/ssh-keys/{$this->key}";
    }
}
