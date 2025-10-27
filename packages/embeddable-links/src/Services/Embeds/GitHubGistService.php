<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services\Embeds;

use ArtisanBuild\EmbeddableLinks\Services\AbstractEmbedService;
use ArtisanBuild\EmbeddableLinks\Services\ParsedUrl;

class GitHubGistService extends AbstractEmbedService
{
    public function supports(ParsedUrl $parsedUrl): bool
    {
        return $parsedUrl->service === 'github-gist';
    }

    public function render(ParsedUrl $parsedUrl, string $url, array $options = []): string
    {
        return view('embeddable-links::embeds.github-gist', [
            'gistId' => $parsedUrl->id,
            'url' => $url,
        ])->render();
    }
}
