<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services\Embeds;

use ArtisanBuild\EmbeddableLinks\Services\AbstractEmbedService;
use ArtisanBuild\EmbeddableLinks\Services\CacheManager;
use ArtisanBuild\EmbeddableLinks\Services\MetadataFetcher;
use ArtisanBuild\EmbeddableLinks\Services\ParsedUrl;

class GenericUrlService extends AbstractEmbedService
{
    public function __construct(
        protected MetadataFetcher $fetcher,
        protected CacheManager $cache,
    ) {}

    public function supports(ParsedUrl $parsedUrl): bool
    {
        return $parsedUrl->service === 'generic';
    }

    public function render(ParsedUrl $parsedUrl, string $url, array $options = []): string
    {
        $metadata = $options['metadata'] ?? $this->fetchMetadata($url, $options);

        // If has OpenGraph image, use OpenGraph template, otherwise use generic
        $view = isset($metadata['image'])
            ? 'embeddable-links::embeds.opengraph'
            : 'embeddable-links::embeds.generic';

        return view($view, [
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
