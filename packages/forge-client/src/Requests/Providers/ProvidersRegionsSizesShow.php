<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Providers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * providers.regions.sizes.show
 *
 * Show the provider region size.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class ProvidersRegionsSizesShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerRegion  The provider region ID
     * @param  int  $providerSize  The provider size ID
     */
    public function __construct(
        protected int $provider,
        protected int $providerRegion,
        protected int $providerSize,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/providers/{$this->provider}/regions/{$this->providerRegion}/sizes/{$this->providerSize}";
    }
}
