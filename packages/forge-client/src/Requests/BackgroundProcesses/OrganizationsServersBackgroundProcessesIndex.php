<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\BackgroundProcesses;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.background-processes.index
 *
 * List all background processes on the server.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersBackgroundProcessesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  null|string  $sort  Available sorts are `user`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-user`.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     * @param  null|string  $filteruser  The user that the process is running as.
     * @param  null|string  $filtersiteId  The site ID that the process is running for.
     * @param  null|string  $filterdirectory  The directory that the process is running in.
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected ?string $sort = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filteruser = null,
        protected ?string $filtersiteId = null,
        protected ?string $filterdirectory = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/background-processes";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'sort' => $this->sort,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[user]' => $this->filteruser,
            'filter[site_id]' => $this->filtersiteId,
            'filter[directory]' => $this->filterdirectory,
        ]);
    }
}
