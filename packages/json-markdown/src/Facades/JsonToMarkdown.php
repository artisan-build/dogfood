<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Convert JSON to Markdown.
 *
 * @method static string convert(string $json)
 *
 * @see \ArtisanBuild\JsonMarkdown\JsonToMarkdown
 */
class JsonToMarkdown extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ArtisanBuild\JsonMarkdown\JsonToMarkdown::class;
    }
}
