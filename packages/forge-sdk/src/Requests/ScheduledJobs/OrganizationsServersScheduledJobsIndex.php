<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\ScheduledJobs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.scheduled-jobs.index
 *
 * List all scheduled jobs associated with the server.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersScheduledJobsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  null|string  $sort  Available sorts are `created_at`, `updated_at`, `status`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected ?string $sort = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filterstatus = null,
        protected ?string $filteruser = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/scheduled-jobs";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'sort' => $this->sort,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[status]' => $this->filterstatus,
            'filter[user]' => $this->filteruser,
        ]);
    }
}
