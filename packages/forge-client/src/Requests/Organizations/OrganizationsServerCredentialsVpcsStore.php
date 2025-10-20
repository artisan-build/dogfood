<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Organizations;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.server-credentials.vpcs.store
 *
 * Create a private network for the provider.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServerCredentialsVpcsStore extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $credential  The credential ID
     */
    public function __construct(
        protected string $organization,
        protected int $credential,
        protected string $region,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/server-credentials/{$this->credential}/regions/{$this->region}/vpcs";
    }
}
