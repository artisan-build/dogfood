<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown\Actions;

use ArtisanBuild\Hallway\TextRendering\Markdown\MarkdownContent;
use Closure;
use Illuminate\Support\Str;

class ConvertParagraphsToFluxText
{
    public function __invoke(MarkdownContent $content, Closure $next)
    {
        $content->parsed = Str::replace('<p ', '<flux:text ', $content->parsed);
        $content->parsed = Str::replace('<p>', '<flux:text>', $content->parsed);
        $content->parsed = Str::replace('</p>', '</flux:text>', $content->parsed);

        $content->preview = Str::replace('<p ', '<flux:text ', $content->preview);
        $content->preview = Str::replace('<p>', '<flux:text>', $content->preview);
        $content->preview = Str::replace('</p>', '</flux:text>', $content->preview);

        return $next($content);
    }
}
