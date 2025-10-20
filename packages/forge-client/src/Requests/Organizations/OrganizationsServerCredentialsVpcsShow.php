<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Organizations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.server-credentials.vpcs.show
 *
 * Get a VPC for the provider.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServerCredentialsVpcsShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $credential  The credential ID
     */
    public function __construct(
        protected string $organization,
        protected int $credential,
        protected string $region,
        protected string $vpcId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/server-credentials/{$this->credential}/regions/{$this->region}/vpcs/{$this->vpcId}";
    }
}
