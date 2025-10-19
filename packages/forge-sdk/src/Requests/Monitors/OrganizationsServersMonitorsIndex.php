<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Monitors;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.monitors.index
 *
 * List all monitors associated with the server.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersMonitorsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  null|string  $sort  Available sorts are `state`, `status`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-state`.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     * @param  null|string  $filterstatus  The status of the monitor.
     * @param  null|string  $filterstate  The state of the monitor.
     * @param  null|string  $filtertype  The type of the monitor.
     * @param  null|string  $filternotify  The email address to notify when the monitor is in an alert state.
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected ?string $sort = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filterstatus = null,
        protected ?string $filterstate = null,
        protected ?string $filtertype = null,
        protected ?string $filternotify = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/monitors";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'sort' => $this->sort,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[status]' => $this->filterstatus,
            'filter[state]' => $this->filterstate,
            'filter[type]' => $this->filtertype,
            'filter[notify]' => $this->filternotify,
        ]);
    }
}
