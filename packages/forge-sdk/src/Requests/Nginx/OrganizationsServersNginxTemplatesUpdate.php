<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Nginx;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.nginx.templates.update
 *
 * Update the specified nginx template on the server.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersNginxTemplatesUpdate extends Request
{
    protected Method $method = Method::PUT;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $nginxTemplate  The nginx template ID
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $nginxTemplate,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/nginx/templates/{$this->nginxTemplate}";
    }
}
