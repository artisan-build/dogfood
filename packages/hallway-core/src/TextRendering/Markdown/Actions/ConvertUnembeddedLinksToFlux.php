<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown\Actions;

use ArtisanBuild\Hallway\TextRendering\Markdown\MarkdownContent;
use Closure;
use Illuminate\Support\Str;

class ConvertUnembeddedLinksToFlux
{
    public function __invoke(MarkdownContent $content, Closure $next)
    {
        $content->parsed = Str::replace('<a ', '<flux:link variant="subtle" external="true" ', $content->parsed);
        $content->parsed = Str::replace('</a>', '</flux:link>', $content->parsed);

        $content->preview = Str::replace('<a ', '<flux:link variant="subtle" external="true" ', $content->preview);
        $content->preview = Str::replace('</a>', '</flux:link>', $content->preview);

        return $next($content);
    }
}
