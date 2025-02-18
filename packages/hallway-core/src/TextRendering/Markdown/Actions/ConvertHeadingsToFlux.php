<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown\Actions;

use ArtisanBuild\Hallway\TextRendering\Markdown\MarkdownContent;
use Closure;
use Illuminate\Support\Str;

class ConvertHeadingsToFlux
{
    public function __invoke(MarkdownContent $content, Closure $next)
    {
        $content->parsed = Str::replace('<h1 ', '<flux:heading size="xl" ', $content->parsed);
        $content->parsed = Str::replace('<h1>', '<flux:heading size="xl">', $content->parsed);
        $content->parsed = Str::replace('</h1>', '</flux:heading>', $content->parsed);

        $content->parsed = Str::replace('<h2 ', '<flux:heading size="lg" ', $content->parsed);
        $content->parsed = Str::replace('<h2>', '<flux:heading size="lg">', $content->parsed);
        $content->parsed = Str::replace('</h2>', '</flux:heading>', $content->parsed);

        $content->parsed = Str::replace('<h3 ', '<flux:heading ', $content->parsed);
        $content->parsed = Str::replace('<h3>', '<flux:heading>', $content->parsed);
        $content->parsed = Str::replace('</h3>', '</flux:heading>', $content->parsed);

        $content->parsed = Str::replace('<h4 ', '<flux:heading ', $content->parsed);
        $content->parsed = Str::replace('<h4>', '<flux:heading>', $content->parsed);
        $content->parsed = Str::replace('</h4>', '</flux:heading>', $content->parsed);

        $content->parsed = Str::replace('<h5 ', '<flux:heading ', $content->parsed);
        $content->parsed = Str::replace('<h5>', '<flux:heading>', $content->parsed);
        $content->parsed = Str::replace('</h5>', '</flux:heading>', $content->parsed);

        $content->preview = Str::replace('<h1 ', '<flux:heading size="xl" ', $content->preview);
        $content->preview = Str::replace('<h1>', '<flux:heading size="xl">', $content->preview);
        $content->preview = Str::replace('</h1>', '</flux:heading>', $content->preview);

        $content->preview = Str::replace('<h2 ', '<flux:heading size="lg" ', $content->preview);
        $content->preview = Str::replace('<h2>', '<flux:heading size="lg">', $content->preview);
        $content->preview = Str::replace('</h2>', '</flux:heading>', $content->preview);

        $content->preview = Str::replace('<h3 ', '<flux:heading ', $content->preview);
        $content->preview = Str::replace('<h3>', '<flux:heading>', $content->preview);
        $content->preview = Str::replace('</h3>', '</flux:heading>', $content->preview);

        $content->preview = Str::replace('<h4 ', '<flux:heading ', $content->preview);
        $content->preview = Str::replace('<h4>', '<flux:heading>', $content->preview);
        $content->preview = Str::replace('</h4>', '</flux:heading>', $content->preview);

        $content->preview = Str::replace('<h5 ', '<flux:heading ', $content->preview);
        $content->preview = Str::replace('<h5>', '<flux:heading>', $content->preview);
        $content->preview = Str::replace('</h5>', '</flux:heading>', $content->preview);

        return $next($content);
    }
}
