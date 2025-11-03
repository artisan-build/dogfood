<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Convert Markdown to JSON.
 *
 * @method static string convert(string $markdown)
 *
 * @see \ArtisanBuild\JsonMarkdown\MarkdownToJson
 */
class MarkdownToJson extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ArtisanBuild\JsonMarkdown\MarkdownToJson::class;
    }
}
