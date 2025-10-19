<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Resource;

use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersArchivesDestroy;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersArchivesIndex;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersArchivesStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersBackgroundProcessesActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersDestroy;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersEventsIndex;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersEventsOutputShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersEventsShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersIndex;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpCliVersionShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpCliVersionUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpMaxExecutionTimeShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpMaxExecutionTimeUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpMaxUploadSizeShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpMaxUploadSizeUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpOpcacheDestroy;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpOpcacheShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpOpcacheStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpSiteVersionShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpSiteVersionUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsConfigsCliShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsConfigsCliUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsConfigsFpmShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsConfigsFpmUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsConfigsPoolShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsConfigsPoolUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsDestroy;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsIndex;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersPhpVersionsUpdate;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersServicesMysqlActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersServicesNginxActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersServicesPhpActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersServicesPostgresActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersServicesRedisActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersServicesSupervisorActionsStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersShow;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsServersStore;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsTeamsServersDestroy;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsTeamsServersIndex;
use ArtisanBuild\ForgeSdk\Requests\Servers\OrganizationsTeamsServersStore;
use ArtisanBuild\ForgeSdk\Resource;
use Saloon\Http\Response;

class Servers extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  string  $sort  Available sorts are `name`, `provider`, `ubuntu_version`, `region`, `php_version`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-name`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filteripAddress  The IP address of the server.
     * @param  string  $filtername  The name of the server.
     * @param  string  $filterregion  The region where the server is located.
     * @param  string  $filtersize  The size of the server.
     * @param  string  $filterprovider  The provider of the server.
     * @param  string  $filterubuntuVersion  The Ubuntu version of the server.
     * @param  string  $filterphpVersion  The PHP version of the server.
     * @param  string  $filterdatabaseType  The database type of the server.
     */
    public function organizationsServersIndex(
        string $organization,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filteripAddress,
        ?string $filtername,
        ?string $filterregion,
        ?string $filtersize,
        ?string $filterprovider,
        ?string $filterubuntuVersion,
        ?string $filterphpVersion,
        ?string $filterdatabaseType,
    ): Response {
        return $this->connector->send(new OrganizationsServersIndex($organization, $sort, $pagesize, $pagecursor, $filteripAddress, $filtername, $filterregion, $filtersize, $filterprovider, $filterubuntuVersion, $filterphpVersion, $filterdatabaseType));
    }

    /**
     * @param  string  $organization  The organization slug
     */
    public function organizationsServersStore(string $organization): Response
    {
        return $this->connector->send(new OrganizationsServersStore($organization));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServersArchivesIndex(
        string $organization,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsServersArchivesIndex($organization, $sort, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     */
    public function organizationsServersArchivesStore(string $organization): Response
    {
        return $this->connector->send(new OrganizationsServersArchivesStore($organization));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersArchivesDestroy(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersArchivesDestroy($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $backgroundProcess  The background process ID
     */
    public function organizationsServersBackgroundProcessesActionsStore(
        string $organization,
        int $server,
        int $backgroundProcess,
    ): Response {
        return $this->connector->send(new OrganizationsServersBackgroundProcessesActionsStore($organization, $server, $backgroundProcess));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersActionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersActionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersServicesNginxActionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersServicesNginxActionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersServicesPostgresActionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersServicesPostgresActionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersServicesRedisActionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersServicesRedisActionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersServicesMysqlActionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersServicesMysqlActionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersServicesPhpActionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersServicesPhpActionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersServicesSupervisorActionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersServicesSupervisorActionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersShow(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersShow($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersDestroy(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersDestroy($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  string  $include  Available includes are `initiator`, `initiatorCount`, `initiatorExists`. You can include multiple options by separating them with a comma.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     * @param  string  $filterinitiatedBy  The user ID of the event initiator.
     * @param  string  $filterranAs  The server user that the event was run as.
     */
    public function organizationsServersEventsIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?string $include,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filterinitiatedBy,
        ?string $filterranAs,
    ): Response {
        return $this->connector->send(new OrganizationsServersEventsIndex($organization, $server, $sort, $include, $pagesize, $pagecursor, $filterinitiatedBy, $filterranAs));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $event  The event ID
     */
    public function organizationsServersEventsShow(string $organization, int $server, int $event): Response
    {
        return $this->connector->send(new OrganizationsServersEventsShow($organization, $server, $event));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $event  The event ID
     */
    public function organizationsServersEventsOutputShow(string $organization, int $server, int $event): Response
    {
        return $this->connector->send(new OrganizationsServersEventsOutputShow($organization, $server, $event));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpCliVersionShow(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpCliVersionShow($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpCliVersionUpdate(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpCliVersionUpdate($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpSiteVersionShow(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpSiteVersionShow($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpSiteVersionUpdate(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpSiteVersionUpdate($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`, `status`, `version`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServersPhpVersionsIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filterstatus,
        ?string $filterversion,
    ): Response {
        return $this->connector->send(new OrganizationsServersPhpVersionsIndex($organization, $server, $sort, $pagesize, $pagecursor, $filterstatus, $filterversion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpVersionsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpVersionsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsShow(string $organization, int $server, int $phpVersion): Response
    {
        return $this->connector->send(new OrganizationsServersPhpVersionsShow($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsUpdate(string $organization, int $server, int $phpVersion): Response
    {
        return $this->connector->send(new OrganizationsServersPhpVersionsUpdate($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsDestroy(string $organization, int $server, int $phpVersion): Response
    {
        return $this->connector->send(new OrganizationsServersPhpVersionsDestroy($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsConfigsFpmShow(
        string $organization,
        int $server,
        int $phpVersion,
    ): Response {
        return $this->connector->send(new OrganizationsServersPhpVersionsConfigsFpmShow($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsConfigsFpmUpdate(
        string $organization,
        int $server,
        int $phpVersion,
    ): Response {
        return $this->connector->send(new OrganizationsServersPhpVersionsConfigsFpmUpdate($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsConfigsCliShow(
        string $organization,
        int $server,
        int $phpVersion,
    ): Response {
        return $this->connector->send(new OrganizationsServersPhpVersionsConfigsCliShow($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsConfigsCliUpdate(
        string $organization,
        int $server,
        int $phpVersion,
    ): Response {
        return $this->connector->send(new OrganizationsServersPhpVersionsConfigsCliUpdate($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsConfigsPoolShow(
        string $organization,
        int $server,
        int $phpVersion,
        ?string $user,
    ): Response {
        return $this->connector->send(new OrganizationsServersPhpVersionsConfigsPoolShow($organization, $server, $phpVersion, $user));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $phpVersion  The php version ID
     */
    public function organizationsServersPhpVersionsConfigsPoolUpdate(
        string $organization,
        int $server,
        int $phpVersion,
    ): Response {
        return $this->connector->send(new OrganizationsServersPhpVersionsConfigsPoolUpdate($organization, $server, $phpVersion));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpMaxUploadSizeShow(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpMaxUploadSizeShow($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpMaxUploadSizeUpdate(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpMaxUploadSizeUpdate($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpMaxExecutionTimeShow(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpMaxExecutionTimeShow($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpMaxExecutionTimeUpdate(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpMaxExecutionTimeUpdate($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpOpcacheShow(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpOpcacheShow($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpOpcacheStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpOpcacheStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersPhpOpcacheDestroy(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersPhpOpcacheDestroy($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsTeamsServersIndex(
        string $organization,
        int $team,
        ?int $pagesize,
        ?string $pagecursor,
    ): Response {
        return $this->connector->send(new OrganizationsTeamsServersIndex($organization, $team, $pagesize, $pagecursor));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     */
    public function organizationsTeamsServersStore(string $organization, int $team): Response
    {
        return $this->connector->send(new OrganizationsTeamsServersStore($organization, $team));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $team  The team ID
     * @param  int  $server  The server ID
     */
    public function organizationsTeamsServersDestroy(string $organization, int $team, int $server): Response
    {
        return $this->connector->send(new OrganizationsTeamsServersDestroy($organization, $team, $server));
    }
}
