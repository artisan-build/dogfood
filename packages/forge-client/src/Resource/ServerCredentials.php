<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\ServerCredentials\OrganizationsTeamsServerCredentialsDestroy;
use ArtisanBuild\ForgeClient\Requests\ServerCredentials\OrganizationsTeamsServerCredentialsIndex;
use ArtisanBuild\ForgeClient\Requests\ServerCredentials\OrganizationsTeamsServerCredentialsStore;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class ServerCredentials extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsTeamsServerCredentialsIndex(
        string $organization,
        int $team,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsTeamsServerCredentialsIndex($organization, $team, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function organizationsTeamsServerCredentialsStore(string $organization, int $team): Response
    {
        return $this->connector->send(new OrganizationsTeamsServerCredentialsStore($organization, $team));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $credential  The credential ID
     */
    public function organizationsTeamsServerCredentialsDestroy(
        string $organization,
        int $team,
        int $credential,
    ): Response {
        return $this->connector->send(new OrganizationsTeamsServerCredentialsDestroy($organization, $team, $credential));
    }
}
