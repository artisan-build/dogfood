<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Providers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * providers.index
 *
 * Show all providers
 *
 * Processing mode: <small><code>sync</code></small>
 */
class ProvidersIndex extends Request
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
        return '/providers';
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
