<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Logs\OrganizationsServersLogsDestroy;
use ArtisanBuild\ForgeSdk\Requests\Logs\OrganizationsServersLogsShow;
use ArtisanBuild\ForgeSdk\Resource;
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
