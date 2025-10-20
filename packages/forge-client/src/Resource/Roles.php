<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Roles\OrganizationsRolesDestroy;
use ArtisanBuild\ForgeClient\Requests\Roles\OrganizationsRolesIndex;
use ArtisanBuild\ForgeClient\Requests\Roles\OrganizationsRolesPermissionsIndex;
use ArtisanBuild\ForgeClient\Requests\Roles\OrganizationsRolesShow;
use ArtisanBuild\ForgeClient\Requests\Roles\OrganizationsRolesStore;
use ArtisanBuild\ForgeClient\Requests\Roles\OrganizationsRolesUpdate;
use ArtisanBuild\ForgeClient\Requests\Roles\PermissionsIndex;
use ArtisanBuild\ForgeClient\Requests\Roles\PermissionsShow;
use ArtisanBuild\ForgeClient\Requests\Roles\PredefinedRolesIndex;
use ArtisanBuild\ForgeClient\Requests\Roles\PredefinedRolesShow;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Roles extends Resource
{
    /**
     * @param  string  $include  Available includes are `permissions`, `permissionsCount`, `permissionsExists`. You can include multiple options by separating them with a comma.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function predefinedRolesIndex(
        ?string $include,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
        ?string $filterpermissionsName,
    ): Response {
        return $this->connector->send(new PredefinedRolesIndex($include, $pagesize, $pagecursor, $filtername, $filterpermissionsName));
    }

    /**
     * @param  int  $role  The role ID
     */
    public function predefinedRolesShow(int $role): Response
    {
        return $this->connector->send(new PredefinedRolesShow($role));
    }

    /**
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function permissionsIndex(?int $pagesize, ?string $pagecursor, ?string $filtername): Response
    {
        return $this->connector->send(new PermissionsIndex($pagesize, $pagecursor, $filtername));
    }

    /**
     * @param  int  $permission  The permission ID
     */
    public function permissionsShow(int $permission): Response
    {
        return $this->connector->send(new PermissionsShow($permission));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  string  $include  Available includes are `permissions`, `permissionsCount`, `permissionsExists`. You can include multiple options by separating them with a comma.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsRolesIndex(
        string $organization,
        ?string $include,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
        ?string $filterpermissionsName,
    ): Response {
        return $this->connector->send(new OrganizationsRolesIndex($organization, $include, $pagesize, $pagecursor, $filtername, $filterpermissionsName));
    }

    /**
     * @param  string  $organization  The organization slug
     */
    public function organizationsRolesStore(string $organization): Response
    {
        return $this->connector->send(new OrganizationsRolesStore($organization));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $role  The role ID
     */
    public function organizationsRolesShow(string $organization, int $role): Response
    {
        return $this->connector->send(new OrganizationsRolesShow($organization, $role));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $role  The role ID
     */
    public function organizationsRolesUpdate(string $organization, int $role): Response
    {
        return $this->connector->send(new OrganizationsRolesUpdate($organization, $role));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $role  The role ID
     */
    public function organizationsRolesDestroy(string $organization, int $role): Response
    {
        return $this->connector->send(new OrganizationsRolesDestroy($organization, $role));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $role  The role ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsRolesPermissionsIndex(
        string $organization,
        int $role,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
    ): Response {
        return $this->connector->send(new OrganizationsRolesPermissionsIndex($organization, $role, $pagesize, $pagecursor, $filtername));
    }
}
