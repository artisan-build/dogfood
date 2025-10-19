<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Organizations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * organizations.index
 *
 * Show all organizations the user has access to.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class OrganizationsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/orgs';
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
