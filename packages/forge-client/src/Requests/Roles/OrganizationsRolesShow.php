<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Roles;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.roles.show
 *
 * Show a specific role for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRolesShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $role  The role ID
     */
    public function __construct(
        protected string $organization,
        protected int $role,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/roles/{$this->role}";
    }
}
