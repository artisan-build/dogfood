<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services;

use ArtisanBuild\EmbeddableLinks\Services\Embeds\GenericUrlService;
use ArtisanBuild\EmbeddableLinks\Services\Embeds\GitHubGistService;
use ArtisanBuild\EmbeddableLinks\Services\Embeds\OpenGraphService;
use ArtisanBuild\EmbeddableLinks\Services\Embeds\VimeoService;
use ArtisanBuild\EmbeddableLinks\Services\Embeds\YouTubeService;

class EmbedManager
{
    protected array $services = [];

    public function __construct(
        protected UrlParser $parser,
        protected CacheManager $cache,
        protected MetadataFetcher $fetcher,
    ) {
        $this->registerServices();
    }

    public function embed(string $url, array $options = []): string
    {
        $parsedUrl = $this->parser->parse($url);
        $service = $this->resolveService($parsedUrl);

        // Check cache unless disabled
        $useCache = $options['cache'] ?? true;
        if ($useCache && $parsedUrl->service === 'generic') {
            $cached = $this->cache->get($url);
            if ($cached !== null) {
                return $service->render($parsedUrl, $url, array_merge($options, ['metadata' => $cached]));
            }
        }

        // For generic URLs, fetch and cache metadata
        if ($parsedUrl->service === 'generic' && $useCache) {
            $metadata = $this->fetcher->fetch($url);
            $this->cache->put($url, $metadata);
            $options['metadata'] = $metadata;
        }

        return $service->render($parsedUrl, $url, $options);
    }

    protected function registerServices(): void
    {
        $this->services = [
            new YouTubeService,
            new VimeoService,
            new GitHubGistService,
            new OpenGraphService($this->fetcher, $this->cache),
            new GenericUrlService($this->fetcher, $this->cache),
        ];
    }

    protected function resolveService(ParsedUrl $parsedUrl): AbstractEmbedService
    {
        foreach ($this->services as $service) {
            if ($service->supports($parsedUrl)) {
                return $service;
            }
        }

        return end($this->services);
    }
}
