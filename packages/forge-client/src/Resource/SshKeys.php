<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\SshKeys\OrganizationsServersKeyShow;
use ArtisanBuild\ForgeClient\Requests\SshKeys\OrganizationsServersKeyUpdate;
use ArtisanBuild\ForgeClient\Requests\SshKeys\OrganizationsServersSshKeysDestroy;
use ArtisanBuild\ForgeClient\Requests\SshKeys\OrganizationsServersSshKeysIndex;
use ArtisanBuild\ForgeClient\Requests\SshKeys\OrganizationsServersSshKeysShow;
use ArtisanBuild\ForgeClient\Requests\SshKeys\OrganizationsServersSshKeysStore;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class SshKeys extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServersSshKeysIndex(
        string $organization,
        int $server,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filtername,
        ?string $filteruser,
    ): Response {
        return $this->connector->send(new OrganizationsServersSshKeysIndex($organization, $server, $pagesize, $pagecursor, $filtername, $filteruser));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersSshKeysStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersSshKeysStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $key  The key ID
     */
    public function organizationsServersSshKeysShow(string $organization, int $server, int $key): Response
    {
        return $this->connector->send(new OrganizationsServersSshKeysShow($organization, $server, $key));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $key  The key ID
     */
    public function organizationsServersSshKeysDestroy(string $organization, int $server, int $key): Response
    {
        return $this->connector->send(new OrganizationsServersSshKeysDestroy($organization, $server, $key));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersKeyShow(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersKeyShow($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersKeyUpdate(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersKeyUpdate($organization, $server));
    }
}
