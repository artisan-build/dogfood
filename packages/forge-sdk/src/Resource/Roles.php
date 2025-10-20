<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Roles\OrganizationsRolesDestroy;
use ArtisanBuild\ForgeSdk\Requests\Roles\OrganizationsRolesIndex;
use ArtisanBuild\ForgeSdk\Requests\Roles\OrganizationsRolesPermissionsIndex;
use ArtisanBuild\ForgeSdk\Requests\Roles\OrganizationsRolesShow;
use ArtisanBuild\ForgeSdk\Requests\Roles\OrganizationsRolesStore;
use ArtisanBuild\ForgeSdk\Requests\Roles\OrganizationsRolesUpdate;
use ArtisanBuild\ForgeSdk\Requests\Roles\PermissionsIndex;
use ArtisanBuild\ForgeSdk\Requests\Roles\PermissionsShow;
use ArtisanBuild\ForgeSdk\Requests\Roles\PredefinedRolesIndex;
use ArtisanBuild\ForgeSdk\Requests\Roles\PredefinedRolesShow;
use ArtisanBuild\ForgeSdk\Resource;
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
