<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Services;

abstract class AbstractEmbedService
{
    abstract public function supports(ParsedUrl $parsedUrl): bool;

    abstract public function render(ParsedUrl $parsedUrl, string $url, array $options = []): string;

    protected function getAspectRatio(array $options): string
    {
        return $options['aspect_ratio'] ?? config('embeddable-links.aspect_ratio', '16/9');
    }
}
