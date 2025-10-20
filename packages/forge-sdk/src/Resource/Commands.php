<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Commands\OrganizationsServersSitesCommandsDestroy;
use ArtisanBuild\ForgeSdk\Requests\Commands\OrganizationsServersSitesCommandsIndex;
use ArtisanBuild\ForgeSdk\Requests\Commands\OrganizationsServersSitesCommandsShow;
use ArtisanBuild\ForgeSdk\Requests\Commands\OrganizationsServersSitesCommandsStore;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class Commands extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  string  $sort  Available sorts are `status`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-status`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filteruserId  The user ID of the command initiator.
     * @param  string  $filterstatus  The status of the command.
     * @param  string  $filtercommand  The command it ran.
     */
    public function organizationsServersSitesCommandsIndex(
        string $organization,
        int $server,
        int $site,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filteruserId,
        ?string $filterstatus,
        ?string $filtercommand,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesCommandsIndex($organization, $server, $site, $sort, $pagesize, $pagecursor, $filteruserId, $filterstatus, $filtercommand));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     */
    public function organizationsServersSitesCommandsStore(string $organization, int $server, int $site): Response
    {
        return $this->connector->send(new OrganizationsServersSitesCommandsStore($organization, $server, $site));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $command  The command ID
     */
    public function organizationsServersSitesCommandsShow(
        string $organization,
        int $server,
        int $site,
        int $command,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesCommandsShow($organization, $server, $site, $command));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  int  $command  The command ID
     */
    public function organizationsServersSitesCommandsDestroy(
        string $organization,
        int $server,
        int $site,
        int $command,
    ): Response {
        return $this->connector->send(new OrganizationsServersSitesCommandsDestroy($organization, $server, $site, $command));
    }
}
