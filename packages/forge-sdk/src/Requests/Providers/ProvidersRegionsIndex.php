<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Providers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * providers.regions.index
 *
 * Show all provider regions
 *
 * Processing mode: <small><code>sync</code></small>
 */
class ProvidersRegionsIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $provider  The provider ID
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected int $provider,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/providers/{$this->provider}/regions";
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
