<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.php.max-upload-size.update
 *
 *
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersPhpMaxUploadSizeUpdate extends Request
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
        return "/orgs/{$this->organization}/servers/{$this->server}/php/max-upload-size";
    }
}
