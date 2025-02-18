<?php

namespace ArtisanBuild\Packagist\Requests;

use Override;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class PackageMetadataRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $vendor,
        protected string $package,
        protected bool $dev = false,
    ) {
        if ($this->dev) {
            // This does not appear to work, but it's implemented as documented
            $this->package .= '-dev';
        }
    }

    #[Override]
    public function resolveEndpoint(): string
    {
        return "/p2/{$this->vendor}/{$this->package}.json";
    }
}
