<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.servers.store
 *
 * Create a new server in the organization. Supports both standard cloud providers
 * and custom VPS
 * configurations.
 *
 * Processing mode: <small><code>async</code></small>
 */
class OrganizationsServersStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $organization  The organization slug
     */
    public function __construct(
        protected string $organization,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers";
    }
}
