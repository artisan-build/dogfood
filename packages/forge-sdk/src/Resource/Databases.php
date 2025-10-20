<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabasePasswordUpdate;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseSchemasDestroy;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseSchemasIndex;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseSchemasShow;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseSchemasStore;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseSchemasSynchronizationsStore;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseUsersDestroy;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseUsersIndex;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseUsersShow;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseUsersStore;
use ArtisanBuild\ForgeSdk\Requests\Databases\OrganizationsServersDatabaseUsersUpdate;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class Databases extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `name`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-name`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filtername  The name of the database schema.
     * @param  string  $filterstatus  The status of the database schema.
     */
    public function organizationsServersDatabaseSchemasIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
        ?string $filterstatus,
    ): Response {
        return $this->connector->send(new OrganizationsServersDatabaseSchemasIndex($organization, $server, $sort, $pagesize, $pagecursor, $filtername, $filterstatus));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  array  $body  The request body
     */
    public function organizationsServersDatabaseSchemasStore(string $organization, int $server, array $body): Response
    {
        $request = new OrganizationsServersDatabaseSchemasStore($organization, $server);
        $request->body()->set($body);

        return $this->connector->send($request);
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $database  The database ID
     */
    public function organizationsServersDatabaseSchemasShow(string $organization, int $server, int $database): Response
    {
        return $this->connector->send(new OrganizationsServersDatabaseSchemasShow($organization, $server, $database));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $database  The database ID
     */
    public function organizationsServersDatabaseSchemasDestroy(
        string $organization,
        int $server,
        int $database,
    ): Response {
        return $this->connector->send(new OrganizationsServersDatabaseSchemasDestroy($organization, $server, $database));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersDatabaseSchemasSynchronizationsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersDatabaseSchemasSynchronizationsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `name`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-name`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filtername  The name of the database user.
     * @param  string  $filterstatus  The status of the database user.
     */
    public function organizationsServersDatabaseUsersIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
        ?string $filterstatus,
    ): Response {
        return $this->connector->send(new OrganizationsServersDatabaseUsersIndex($organization, $server, $sort, $pagesize, $pagecursor, $filtername, $filterstatus));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersDatabaseUsersStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersDatabaseUsersStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $databaseUser  The database user ID
     */
    public function organizationsServersDatabaseUsersShow(string $organization, int $server, int $databaseUser): Response
    {
        return $this->connector->send(new OrganizationsServersDatabaseUsersShow($organization, $server, $databaseUser));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $databaseUser  The database user ID
     */
    public function organizationsServersDatabaseUsersUpdate(
        string $organization,
        int $server,
        int $databaseUser,
    ): Response {
        return $this->connector->send(new OrganizationsServersDatabaseUsersUpdate($organization, $server, $databaseUser));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $databaseUser  The database user ID
     */
    public function organizationsServersDatabaseUsersDestroy(
        string $organization,
        int $server,
        int $databaseUser,
    ): Response {
        return $this->connector->send(new OrganizationsServersDatabaseUsersDestroy($organization, $server, $databaseUser));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersDatabasePasswordUpdate(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersDatabasePasswordUpdate($organization, $server));
    }
}
