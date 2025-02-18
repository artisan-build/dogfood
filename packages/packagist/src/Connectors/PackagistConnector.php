<?php

namespace ArtisanBuild\Packagist\Connectors;

use Override;
use Saloon\Http\Connector;

class PackagistConnector extends Connector
{
    #[Override]
    public function resolveBaseUrl(): string
    {
        return 'https://packagist.org/';
    }

    #[Override]
    public function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'User-Agent' => 'ArtisanBuild/Packagist (+https://www.github.com/artisan-build/packagist)',
        ];
    }
}
