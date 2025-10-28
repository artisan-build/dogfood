<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services\Embeds;

use ArtisanBuild\EmbeddableLinks\Services\AbstractEmbedService;
use ArtisanBuild\EmbeddableLinks\Services\CacheManager;
use ArtisanBuild\EmbeddableLinks\Services\MetadataFetcher;
use ArtisanBuild\EmbeddableLinks\Services\ParsedUrl;

class OpenGraphService extends AbstractEmbedService
{
    public function __construct(
        protected MetadataFetcher $fetcher,
        protected CacheManager $cache,
    ) {}

    public function supports(ParsedUrl $parsedUrl): bool
    {
        // This service handles generic URLs that have OpenGraph metadata
        // We'll check for OpenGraph in the generic service instead
        return false;
    }

    public function render(ParsedUrl $parsedUrl, string $url, array $options = []): string
    {
        $metadata = $options['metadata'] ?? $this->fetchMetadata($url, $options);

        return view('embeddable-links::embeds.opengraph', [
            'metadata' => $metadata,
            'url' => $url,
        ])->render();
    }

    protected function fetchMetadata(string $url, array $options): array
    {
        $useCache = $options['cache'] ?? true;

        if ($useCache && $this->cache->has($url)) {
            return $this->cache->get($url);
        }

        $metadata = $this->fetcher->fetch($url);

        if ($useCache) {
            $this->cache->put($url, $metadata);
        }

        return $metadata;
    }
}
