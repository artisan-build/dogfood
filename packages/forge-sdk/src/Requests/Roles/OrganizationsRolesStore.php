<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Roles;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * organizations.roles.store
 *
 * Create a new role for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRolesStore extends Request implements HasBody
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
        return "/orgs/{$this->organization}/roles";
    }
}
