<?php

namespace ArtisanBuild\Packagist\Requests;

use Override;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListPopularPackagesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $page = 1,
        protected int $per_page = 100,
    ) {}

    #[Override]
    public function resolveEndpoint(): string
    {
        return '/explore/popular.json';
    }

    #[Override]
    public function defaultQuery(): array
    {
        return array_filter([
            'per_page' => $this->per_page,
            'page' => $this->page,
        ]);
    }
}
