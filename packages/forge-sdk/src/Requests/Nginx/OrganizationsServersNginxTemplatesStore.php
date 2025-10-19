<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Nginx;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.servers.nginx.templates.store
 *
 * Create a new nginx template on the server.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersNginxTemplatesStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

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
        return "/orgs/{$this->organization}/servers/{$this->server}/nginx/templates";
    }
}
