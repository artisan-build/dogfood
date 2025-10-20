<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Requests\Providers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * providers.regions.show
 *
 * Show the provider region.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class ProvidersRegionsShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerRegion  The provider region ID
     */
    public function __construct(
        protected int $provider,
        protected int $providerRegion,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/providers/{$this->provider}/regions/{$this->providerRegion}";
    }
}
