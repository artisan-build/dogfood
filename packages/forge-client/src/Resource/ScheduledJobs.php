<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Resource;

use ArtisanBuild\ForgeClient\Requests\ScheduledJobs\OrganizationsServersScheduledJobsDestroy;
use ArtisanBuild\ForgeClient\Requests\ScheduledJobs\OrganizationsServersScheduledJobsIndex;
use ArtisanBuild\ForgeClient\Requests\ScheduledJobs\OrganizationsServersScheduledJobsOutputsShow;
use ArtisanBuild\ForgeClient\Requests\ScheduledJobs\OrganizationsServersScheduledJobsShow;
use ArtisanBuild\ForgeClient\Requests\ScheduledJobs\OrganizationsServersScheduledJobsStore;
use ArtisanBuild\ForgeClient\Resource;
use Saloon\Http\Response;

class ScheduledJobs extends Resource
{
    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  string  $sort  Available sorts are `created_at`, `updated_at`, `status`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  int  $pagesize  The number of results that will be returned per page.
     * @param  string  $pagecursor  The cursor to start the pagination from.
     */
    public function organizationsServersScheduledJobsIndex(
        string $organization,
        int $server,
        ?string $sort,
        ?int $pagesize,
        ?string $pagecursor,
        ?string $filterstatus,
        ?string $filteruser,
    ): Response {
        return $this->connector->send(new OrganizationsServersScheduledJobsIndex($organization, $server, $sort, $pagesize, $pagecursor, $filterstatus, $filteruser));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     */
    public function organizationsServersScheduledJobsStore(string $organization, int $server): Response
    {
        return $this->connector->send(new OrganizationsServersScheduledJobsStore($organization, $server));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $job  The job ID
     */
    public function organizationsServersScheduledJobsShow(string $organization, int $server, int $job): Response
    {
        return $this->connector->send(new OrganizationsServersScheduledJobsShow($organization, $server, $job));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $job  The job ID
     */
    public function organizationsServersScheduledJobsDestroy(string $organization, int $server, int $job): Response
    {
        return $this->connector->send(new OrganizationsServersScheduledJobsDestroy($organization, $server, $job));
    }

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $job  The job ID
     */
    public function organizationsServersScheduledJobsOutputsShow(string $organization, int $server, int $job): Response
    {
        return $this->connector->send(new OrganizationsServersScheduledJobsOutputsShow($organization, $server, $job));
    }
}
