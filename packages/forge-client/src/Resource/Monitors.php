<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Monitors\OrganizationsServersMonitorsDestroy;
use ArtisanBuild\ForgeClient\Requests\Monitors\OrganizationsServersMonitorsIndex;
use ArtisanBuild\ForgeClient\Requests\Monitors\OrganizationsServersMonitorsShow;
use ArtisanBuild\ForgeClient\Requests\Monitors\OrganizationsServersMonitorsStore;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Monitors extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `state`, `status`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-state`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filterstatus  The status of the monitor.
     * @param  string  $filterstate  The state of the monitor.
     * @param  string  $filtertype  The type of the monitor.
     * @param  string  $filternotify  The email address to notify when the monitor is in an alert state.
     */
    public function organizationsServersMonitorsIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filterstatus,
        ?string $filterstate,
        ?string $filtertype,
        ?string $filternotify,
    ): Response {
        return $this->connector->send(new OrganizationsServersMonitorsIndex($organization, $server, $sort, $pagesize, $pagecursor, $filterstatus, $filterstate, $filtertype, $filternotify));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersMonitorsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersMonitorsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $monitor  The monitor ID
     */
    public function organizationsServersMonitorsShow(string $organization, int $server, int $monitor): Response
    {
        return $this->connector->send(new OrganizationsServersMonitorsShow($organization, $server, $monitor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $monitor  The monitor ID
     */
    public function organizationsServersMonitorsDestroy(string $organization, int $server, int $monitor): Response
    {
        return $this->connector->send(new OrganizationsServersMonitorsDestroy($organization, $server, $monitor));
    }
}
