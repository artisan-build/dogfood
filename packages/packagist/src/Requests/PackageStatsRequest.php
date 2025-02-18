<?php

namespace ArtisanBuild\Packagist\Requests;

use Override;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class PackageStatsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $vendor,
        protected string $package,
    ) {}

    #[Override]
    public function resolveEndpoint(): string
    {
        return "/packages/{$this->vendor}/{$this->package}/stats.json";
    }
}
