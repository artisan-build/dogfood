<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Logs\OrganizationsServersLogsDestroy;
use ArtisanBuild\ForgeClient\Requests\Logs\OrganizationsServersLogsShow;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Logs extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersLogsShow(string $organization, int $server, string $key): Response
    {
        return $this->connector->send(new OrganizationsServersLogsShow($organization, $server, $key));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersLogsDestroy(string $organization, int $server, string $key): Response
    {
        return $this->connector->send(new OrganizationsServersLogsDestroy($organization, $server, $key));
    }
}
