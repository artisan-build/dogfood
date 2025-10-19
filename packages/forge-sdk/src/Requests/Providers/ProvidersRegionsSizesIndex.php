<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Providers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * providers.regions.sizes.index
 *
 * Show all provider region sizes
 *
 * Processing mode: <small><code>sync</code></small>
 */
class ProvidersRegionsSizesIndex extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerRegion  The provider region ID
     * @param  null|int  $pagesize  The number of results that will be returned per page.
     * @param  null|string  $pagecursor  The cursor to start the pagination from.
     */
    public function __construct(
        protected int $provider,
        protected int $providerRegion,
        protected ?int $pagesize = null,
        protected ?string $pagecursor = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/providers/{$this->provider}/regions/{$this->providerRegion}/sizes";
    }

    public function defaultQuery(): array
    {
        return array_filter(['page[size]' => $this->pagesize, 'page[cursor]' => $this->pagecursor]);
    }
}
