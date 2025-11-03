<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Convert directories of Markdown files to/from JSON.
 *
 * @method static string toJson(string $path)
 * @method static void fromJson(string $json, string $basePath)
 *
 * @see \ArtisanBuild\JsonMarkdown\MarkdownDirectory
 */
class MarkdownDirectory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ArtisanBuild\JsonMarkdown\MarkdownDirectory::class;
    }
}
