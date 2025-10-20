<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\FirewallRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.firewall-rules.index
 *
 * List all firewall rules associated with the server.
 *
 * Processing mode:
 * <small><code>sync</code></small>
 */
class OrganizationsServersFirewallRulesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  null|string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     * @param  null|string  $filtername  The name of the firewall rule.
     * @param  null|string  $filterstatus  The status of the firewall rule.
     * @param  null|string  $filteripAddress  The IP address or subnet for the firewall rule.
     * @param  null|string  $filtertype  The type of the firewall rule.
     * @param  null|string  $filterport  The port or port range for the firewall rule.
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected ?string $sort = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filtername = null,
        protected ?string $filterstatus = null,
        protected ?string $filteripAddress = null,
        protected ?string $filtertype = null,
        protected ?string $filterport = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/firewall-rules";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'sort' => $this->sort,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[name]' => $this->filtername,
            'filter[status]' => $this->filterstatus,
            'filter[ip_address]' => $this->filteripAddress,
            'filter[type]' => $this->filtertype,
            'filter[port]' => $this->filterport,
        ]);
    }
}
