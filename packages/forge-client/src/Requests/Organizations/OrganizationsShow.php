<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Organizations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.show
 *
 * Show a specific organization for the user.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     */
    public function __construct(
        protected string $organization,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}";
    }
}
