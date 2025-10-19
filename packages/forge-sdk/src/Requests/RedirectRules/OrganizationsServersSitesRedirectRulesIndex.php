<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\RedirectRules;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.redirect-rules.index
 *
 * List all redirect rules associated with the site.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersSitesRedirectRulesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  null|string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     * @param  null|string  $filterfrom  The source URL path for the redirect rule.
     * @param  null|string  $filterto  The destination URL path for the redirect rule.
     * @param  null|string  $filtertype  The type of the redirect rule.
     * @param  null|string  $filterstatus  The status of the redirect rule.
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected ?string $sort = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filterfrom = null,
        protected ?string $filterto = null,
        protected ?string $filtertype = null,
        protected ?string $filterstatus = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/redirect-rules";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'sort' => $this->sort,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[from]' => $this->filterfrom,
            'filter[to]' => $this->filterto,
            'filter[type]' => $this->filtertype,
            'filter[status]' => $this->filterstatus,
        ]);
    }
}
