<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Deployments;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.sites.webhooks.index
 *
 * List all webhooks for the site.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersSitesWebhooksIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  int  $server  The server ID
     * @param  int  $site  The site ID
     * @param  null|string  $sort  Available sorts are `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-created_at`.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected string $organization,
        protected int $server,
        protected int $site,
        protected ?string $sort = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers/{$this->server}/sites/{$this->site}/webhooks";
    }

    public function defaultQuery(): array
    {
        return array_filter(['sort' => $this->sort, 'page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
