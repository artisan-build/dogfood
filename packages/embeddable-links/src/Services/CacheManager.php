<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services;

use Illuminate\Support\Facades\Cache;

class CacheManager
{
    protected string $prefix = 'embeddable-links:';

    protected bool $enabled = true;

    /**
     * Store metadata in cache.
     */
    public function put(string $url, array $metadata): void
    {
        if (! $this->enabled) {
            return;
        }

        $key = $this->generateKey($url);
        $ttl = config('embeddable-links.cache_ttl', 86400);

        Cache::put($key, $metadata, $ttl);
    }

    /**
     * Retrieve metadata from cache.
     */
    public function get(string $url): ?array
    {
        if (! $this->enabled) {
            return null;
        }

        $key = $this->generateKey($url);
        $data = Cache::get($key);

        return is_array($data) ? $data : null;
    }

    /**
     * Check if metadata exists in cache.
     */
    public function has(string $url): bool
    {
        $key = $this->generateKey($url);

        return Cache::has($key);
    }

    /**
     * Remove metadata from cache.
     */
    public function forget(string $url): void
    {
        $key = $this->generateKey($url);
        Cache::forget($key);
    }

    /**
     * Determine if caching should be used.
     */
    public function shouldCache(bool $cache = true): bool
    {
        return $this->enabled && $cache;
    }

    /**
     * Disable caching temporarily.
     */
    public function disable(): static
    {
        $this->enabled = false;

        return $this;
    }

    /**
     * Enable caching.
     */
    public function enable(): static
    {
        $this->enabled = true;

        return $this;
    }

    /**
     * Generate cache key from URL.
     */
    protected function generateKey(string $url): string
    {
        return $this->prefix.md5($url);
    }
}
