<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class MetadataFetcher
{
    public function fetch(string $url): array
    {
        try {
            $response = Http::timeout(config('embeddable-links.http_timeout', 5))
                ->get($url);

            if (! $response->successful()) {
                return $this->getDefaultMetadata($url);
            }

            $html = $response->body();
            $crawler = new Crawler($html);

            return $this->extractMetadata($crawler, $url);
        } catch (\Exception) {
            return $this->getDefaultMetadata($url);
        }
    }

    protected function extractMetadata(Crawler $crawler, string $url): array
    {
        $metadata = [
            'title' => $this->extractTitle($crawler) ?? 'Untitled Page',
            'description' => $this->extractDescription($crawler),
            'image' => $this->extractImage($crawler),
            'url' => $url,
        ];

        // Filter out null values but keep title and url
        $metadata = array_filter($metadata, fn ($value, $key) => $value !== null || in_array($key, ['title', 'url']), ARRAY_FILTER_USE_BOTH);

        return $metadata;
    }

    protected function extractTitle(Crawler $crawler): ?string
    {
        // Try OpenGraph first
        try {
            $ogTitle = $crawler->filterXPath('//meta[@property="og:title"]')->first();
            if ($ogTitle->count() > 0) {
                $content = $ogTitle->attr('content');
                if ($content) {
                    return $content;
                }
            }
        } catch (\Exception) {
            // Continue to fallback
        }

        // Fall back to title tag
        try {
            return $crawler->filter('title')->text();
        } catch (\Exception) {
            return null;
        }
    }

    protected function extractDescription(Crawler $crawler): ?string
    {
        // Try OpenGraph first
        try {
            $ogDesc = $crawler->filterXPath('//meta[@property="og:description"]')->first();
            if ($ogDesc->count() > 0) {
                $content = $ogDesc->attr('content');
                if ($content) {
                    return $content;
                }
            }
        } catch (\Exception) {
            // Continue to fallback
        }

        // Try standard meta description
        try {
            $metaDesc = $crawler->filterXPath('//meta[@name="description"]')->first();
            if ($metaDesc->count() > 0) {
                $content = $metaDesc->attr('content');
                if ($content) {
                    return $content;
                }
            }
        } catch (\Exception) {
            // Continue to fallback
        }

        // Fall back to first paragraph
        try {
            $firstParagraph = $crawler->filter('p')->first()->text();

            return $this->truncateText($firstParagraph, 200);
        } catch (\Exception) {
            return null;
        }
    }

    protected function extractImage(Crawler $crawler): ?string
    {
        try {
            $ogImage = $crawler->filterXPath('//meta[@property="og:image"]')->first();
            if ($ogImage->count() > 0) {
                return $ogImage->attr('content');
            }
        } catch (\Exception) {
            // No image found
        }

        return null;
    }

    protected function truncateText(string $text, int $length = 200): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length).'...';
    }

    protected function getDefaultMetadata(string $url): array
    {
        return [
            'title' => 'Untitled Page',
            'url' => $url,
        ];
    }
}
