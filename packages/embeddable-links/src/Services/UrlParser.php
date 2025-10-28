<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services;

use InvalidArgumentException;

readonly class UrlParser
{
    /**
     * Parse a URL and determine its service type and identifier.
     */
    public function parse(string $url): ParsedUrl
    {
        if (! $this->isValid($url)) {
            throw new InvalidArgumentException("Invalid URL: {$url}");
        }

        if ($this->isYouTube($url)) {
            return new ParsedUrl('youtube', $this->extractYouTubeId($url));
        }

        if ($this->isVimeo($url)) {
            return new ParsedUrl('vimeo', $this->extractVimeoId($url));
        }

        if ($this->isGitHubGist($url)) {
            return new ParsedUrl('github-gist', $this->extractGistId($url));
        }

        return new ParsedUrl('generic', null);
    }

    /**
     * Check if a URL is valid.
     */
    public function isValid(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        return in_array($scheme, ['http', 'https'], true);
    }

    protected function isYouTube(string $url): bool
    {
        return (bool) preg_match('/(?:youtube\.com|youtu\.be)/', $url);
    }

    protected function extractYouTubeId(string $url): ?string
    {
        // Standard: youtube.com/watch?v=VIDEO_ID
        if (preg_match('/[?&]v=([^&]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Shortened: youtu.be/VIDEO_ID
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            return $matches[1];
        }

        // Embed: youtube.com/embed/VIDEO_ID
        if (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function isVimeo(string $url): bool
    {
        return (bool) preg_match('/vimeo\.com/', $url);
    }

    protected function extractVimeoId(string $url): ?string
    {
        // Standard: vimeo.com/VIDEO_ID
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        // Player: player.vimeo.com/video/VIDEO_ID
        if (preg_match('/player\.vimeo\.com\/video\/(\d+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function isGitHubGist(string $url): bool
    {
        return (bool) preg_match('/gist\.github\.com/', $url);
    }

    protected function extractGistId(string $url): ?string
    {
        // GitHub Gist: gist.github.com/username/GIST_ID
        if (preg_match('/gist\.github\.com\/[^\/]+\/([a-f0-9]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

/**
 * Represents a parsed URL with service type and optional identifier.
 */
readonly class ParsedUrl
{
    public function __construct(
        public string $service,
        public ?string $id = null,
    ) {}
}
