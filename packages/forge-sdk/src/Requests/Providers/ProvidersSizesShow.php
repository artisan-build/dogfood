<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Requests\Providers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * providers.sizes.show
 *
 * Show the provider size.
 *
 * Processing mode: <small><code>sync</code></small>
 */
class ProvidersSizesShow extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  int  $provider  The provider ID
     * @param  int  $providerSize  The provider size ID
     */
    public function __construct(
        protected int $provider,
        protected int $providerSize,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/providers/{$this->provider}/sizes/{$this->providerSize}";
    }
}
