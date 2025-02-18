<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown;

class MarkdownContent
{
    public string $parsed = '';
    public string $preview = '';
    public string $original = '';

    public array $media = [];

    public function __construct(public string $content)
    {
        $this->original = $content;
    }

    public static function make(string $content): MarkdownContent
    {
        return new self($content);
    }
}
