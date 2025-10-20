<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Roles;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.roles.permissions.index
 *
 * Show all permissions for the role.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsRolesPermissionsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $role  The role ID
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected int $role,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filtername = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/roles/{$this->role}/permissions";
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor, 'filter[name]' => $this->filtername]);
    }
}
