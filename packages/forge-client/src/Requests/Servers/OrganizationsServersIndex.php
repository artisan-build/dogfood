<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Servers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.servers.index
 *
 * Show all servers for the organization.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsServersIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  string  $organization  The organization slug
     * @param  null|string  $sort  Available sorts are `name`, `provider`, `ubuntu_version`, `region`, `php_version`, `created_at`, `updated_at`. You can sort by multiple options by separating them with a comma. To sort in descending order, use `-` sign in front of the sort, for example: `-name`.
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     * @param  null|string  $filteripAddress  The IP address of the server.
     * @param  null|string  $filtername  The name of the server.
     * @param  null|string  $filterregion  The region where the server is located.
     * @param  null|string  $filtersize  The size of the server.
     * @param  null|string  $filterprovider  The provider of the server.
     * @param  null|string  $filterubuntuVersion  The Ubuntu version of the server.
     * @param  null|string  $filterphpVersion  The PHP version of the server.
     * @param  null|string  $filterdatabaseType  The database type of the server.
     */
    public function __construct(
        protected string $organization,
        protected ?string $sort = null,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filteripAddress = null,
        protected ?string $filtername = null,
        protected ?string $filterregion = null,
        protected ?string $filtersize = null,
        protected ?string $filterprovider = null,
        protected ?string $filterubuntuVersion = null,
        protected ?string $filterphpVersion = null,
        protected ?string $filterdatabaseType = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orgs/{$this->organization}/servers";
    }

    public function defaultQuery(): array
    {
        return array_filter([
            'sort' => $this->sort,
            'page[size]' => $this->pagesize,
            'page[cursor]' => $this->pagecursor,
            'filter[ip_address]' => $this->filteripAddress,
            'filter[name]' => $this->filtername,
            'filter[region]' => $this->filterregion,
            'filter[size]' => $this->filtersize,
            'filter[provider]' => $this->filterprovider,
            'filter[ubuntu_version]' => $this->filterubuntuVersion,
            'filter[php_version]' => $this->filterphpVersion,
            'filter[database_type]' => $this->filterdatabaseType,
        ]);
    }
}
