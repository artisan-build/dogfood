<?php

namespace ArtisanBuild\Bench\Packagist;

use Illuminate\Support\Facades\Http;

class GetGitHubRepositoryFromPackagistRecord
{
    public function __invoke(string $vendor, string $package): ?string
    {
        return Http::get("https://packagist.org/packages/{$vendor}/{$package}.json")->json('package.repository');
    }
}
