<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsDestroy;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsIndex;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsInvitesDestroy;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsInvitesIndex;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsInvitesShow;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsInvitesStore;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsMembersDestroy;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsMembersIndex;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsMembersShow;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsMembersUpdate;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsShow;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsStore;
use ArtisanBuild\ForgeClient\Requests\Teams\OrganizationsTeamsUpdate;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class Teams extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsTeamsIndex(string $organization, ?int $pagesize, ?string $pagecursor): Response
    {
        return $this->connector->send(new OrganizationsTeamsIndex($organization, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     */
    public function organizationsTeamsStore(string $organization): Response
    {
        return $this->connector->send(new OrganizationsTeamsStore($organization));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function organizationsTeamsShow(string $organization, int $team): Response
    {
        return $this->connector->send(new OrganizationsTeamsShow($organization, $team));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function organizationsTeamsUpdate(string $organization, int $team): Response
    {
        return $this->connector->send(new OrganizationsTeamsUpdate($organization, $team));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function organizationsTeamsDestroy(string $organization, int $team): Response
    {
        return $this->connector->send(new OrganizationsTeamsDestroy($organization, $team));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsTeamsMembersIndex(
        string $organization,
        int $team,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsTeamsMembersIndex($organization, $team, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $user  The user ID
     */
    public function organizationsTeamsMembersShow(string $organization, int $team, int $user): Response
    {
        return $this->connector->send(new OrganizationsTeamsMembersShow($organization, $team, $user));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $user  The user ID
     */
    public function organizationsTeamsMembersUpdate(string $organization, int $team, int $user): Response
    {
        return $this->connector->send(new OrganizationsTeamsMembersUpdate($organization, $team, $user));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $user  The user ID
     */
    public function organizationsTeamsMembersDestroy(string $organization, int $team, int $user): Response
    {
        return $this->connector->send(new OrganizationsTeamsMembersDestroy($organization, $team, $user));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  string  $include  Available includes are `role`, `roleCount`, `roleExists`, `team`, `teamCount`, `teamExists`, `organization`, `organizationCount`, `organizationExists`. You can include multiple options by separating them with a comma.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsTeamsInvitesIndex(
        string $organization,
        int $team,
        ?string $include,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsTeamsInvitesIndex($organization, $team, $include, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function organizationsTeamsInvitesStore(string $organization, int $team): Response
    {
        return $this->connector->send(new OrganizationsTeamsInvitesStore($organization, $team));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $invitation  The invitation ID
     */
    public function organizationsTeamsInvitesShow(string $organization, int $team, int $invitation): Response
    {
        return $this->connector->send(new OrganizationsTeamsInvitesShow($organization, $team, $invitation));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $invitation  The invitation ID
     */
    public function organizationsTeamsInvitesDestroy(string $organization, int $team, int $invitation): Response
    {
        return $this->connector->send(new OrganizationsTeamsInvitesDestroy($organization, $team, $invitation));
    }
}
