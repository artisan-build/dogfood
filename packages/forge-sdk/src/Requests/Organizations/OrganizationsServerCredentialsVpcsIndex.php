<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Organizations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.server-credentials.vpcs.index
 *
 * List VPCs for the provider.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServerCredentialsVpcsIndex extends Request
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
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/server-credentials/{$this->credential}/regions/{$this->region}/vpcs";
    }
}
