<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Roles;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * permissions.index
 *
 * Show all permissions.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class PermissionsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
        protected ?string $filtername = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/permissions';
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor, 'filter[name]' => $this->filtername]);
    }
}
