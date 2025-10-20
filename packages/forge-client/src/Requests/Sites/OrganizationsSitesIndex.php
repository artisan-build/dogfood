<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Sites;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.sites.index
 *
 * Show all sites for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsSitesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  null|string  $include  Available includes are `tags`, `tagsCount`, `tagsExists`, `latestDeployment`, `latestDeploymentCount`, `latestDeploymentExists`. You can include multiple options by separating them with a comma.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected ?string $include = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filtername = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/sites";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'include' => $this->include,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[name]' => $this->filtername,
        ]);
    }
}
