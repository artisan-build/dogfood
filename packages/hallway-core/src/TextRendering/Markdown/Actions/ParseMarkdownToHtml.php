<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown\Actions;

use ArtisanBuild\Hallway\TextRendering\Markdown\MarkdownContent;
use Closure;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class ParseMarkdownToHtml
{
    private MarkdownConverter $converter;

    public function __invoke(MarkdownContent $content, Closure $next)
    {
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new AttributesExtension);

        $this->converter = new MarkdownConverter($environment);

        $content->parsed = $this->converter->convert($content->content)->getContent();

        return $next($content);
    }
}
