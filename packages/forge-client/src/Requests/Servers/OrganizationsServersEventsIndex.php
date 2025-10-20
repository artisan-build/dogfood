<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.events.index
 *
 * List all events associated with the server.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersEventsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  null|string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  null|string  $include  Available includes are `initiator`, `initiatorCount`, `initiatorExists`. You can include multiple options by separating them with a comma.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     * @param  null|string  $filterinitiatedBy  The user ID of the event initiator.
     * @param  null|string  $filterranAs  The server user that the event was run as.
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected ?string $sort = null,
        protected ?string $include = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filterinitiatedBy = null,
        protected ?string $filterranAs = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/events";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'sort' => $this->sort,
            'include' => $this->include,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[initiated_by]' => $this->filterinitiatedBy,
            'filter[ran_as]' => $this->filterranAs,
        ]);
    }
}
