<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown;

use ArtisanBuild\Hallway\TextRendering\Contracts\HandlesEmbeddableLinks;
use Embed\Embed;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class ConvertMarkdownToHtml
{
    private MarkdownConverter $converter;

    public function __invoke(string $content): string
    {
        $embedLibrary = new Embed();
        $embedLibrary->setSettings([
            'oembed:query_parameters' => [
                'maxwidth' => 800,
                'maxheight' => 600,
            ],
        ]);

        $environment = new Environment([
            'embed' => [
                'adapter' => new OscaroteroEmbedAdapter($embedLibrary),
                'allowed_domains' => [],
                'fallback' => 'link',
            ],
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AttributesExtension());
        $environment->addExtension(new EmbedExtension());

        $this->converter = new MarkdownConverter($environment);

        $content = app(HandlesEmbeddableLinks::class)($content);

        return Cache::rememberForever(sha1($content), fn() => $this->converter->convert($content)->getContent());

    }

}
