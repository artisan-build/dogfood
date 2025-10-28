<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services\Embeds;

use ArtisanBuild\EmbeddableLinks\Services\AbstractEmbedService;
use ArtisanBuild\EmbeddableLinks\Services\ParsedUrl;

class YouTubeService extends AbstractEmbedService
{
    public function supports(ParsedUrl $parsedUrl): bool
    {
        return $parsedUrl->service === 'youtube';
    }

    public function render(ParsedUrl $parsedUrl, string $url, array $options = []): string
    {
        $aspectRatio = $this->getAspectRatio($options);

        return view('embeddable-links::embeds.youtube', [
            'videoId' => $parsedUrl->id,
            'aspectRatio' => $aspectRatio,
        ])->render();
    }
}
