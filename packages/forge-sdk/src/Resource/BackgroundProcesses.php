<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\BackgroundProcesses\OrganizationsServersBackgroundProcessesDestroy;
use ArtisanBuild\ForgeSdk\Requests\BackgroundProcesses\OrganizationsServersBackgroundProcessesIndex;
use ArtisanBuild\ForgeSdk\Requests\BackgroundProcesses\OrganizationsServersBackgroundProcessesLogShow;
use ArtisanBuild\ForgeSdk\Requests\BackgroundProcesses\OrganizationsServersBackgroundProcessesShow;
use ArtisanBuild\ForgeSdk\Requests\BackgroundProcesses\OrganizationsServersBackgroundProcessesStore;
use ArtisanBuild\ForgeSdk\Requests\BackgroundProcesses\OrganizationsServersBackgroundProcessesUpdate;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class BackgroundProcesses extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `user`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-user`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filteruser  The user that the process is running as.
     * @param  string  $filtersiteId  The site ID that the process is running for.
     * @param  string  $filterdirectory  The directory that the process is running in.
     */
    public function organizationsServersBackgroundProcessesIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filteruser,
        ?string $filtersiteId,
        ?string $filterdirectory,
    ): Response {
        return $this->connector->send(new OrganizationsServersBackgroundProcessesIndex($organization, $server, $sort, $pagesize, $pagecursor, $filteruser, $filtersiteId, $filterdirectory));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersBackgroundProcessesStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersBackgroundProcessesStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $backgroundProcess  The background process ID
     */
    public function organizationsServersBackgroundProcessesShow(
        string $organization,
        int $server,
        int $backgroundProcess,
    ): Response {
        return $this->connector->send(new OrganizationsServersBackgroundProcessesShow($organization, $server, $backgroundProcess));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $backgroundProcess  The background process ID
     */
    public function organizationsServersBackgroundProcessesUpdate(
        string $organization,
        int $server,
        int $backgroundProcess,
    ): Response {
        return $this->connector->send(new OrganizationsServersBackgroundProcessesUpdate($organization, $server, $backgroundProcess));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $backgroundProcess  The background process ID
     */
    public function organizationsServersBackgroundProcessesDestroy(
        string $organization,
        int $server,
        int $backgroundProcess,
    ): Response {
        return $this->connector->send(new OrganizationsServersBackgroundProcessesDestroy($organization, $server, $backgroundProcess));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $backgroundProcess  The background process ID
     */
    public function organizationsServersBackgroundProcessesLogShow(
        string $organization,
        int $server,
        int $backgroundProcess,
    ): Response {
        return $this->connector->send(new OrganizationsServersBackgroundProcessesLogShow($organization, $server, $backgroundProcess));
    }
}
