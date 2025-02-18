<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\TextRendering\Markdown\Actions;

use ArtisanBuild\Hallway\TextRendering\Markdown\MarkdownContent;
use Closure;
use Embed\Embed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class ExtractEmbeddableLinks
{
    private MarkdownConverter $converter;

    public function __invoke(MarkdownContent $content, Closure $next)
    {
        $lines = explode("\n", $content->content);

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (filter_var($line, FILTER_VALIDATE_URL)) {
                $linted = $this->getEmbeddedContent($line);
                if (Str::contains($linted, '<iframe')) {
                    $content->media[] = [
                        'link' => $line,
                        'linted' => $this->getEmbeddedContent($line),
                    ];
                    unset($lines[$index]);
                }
            }
        }

        $content->content = implode("\n", $lines);

        return $next($content);
    }

    private function getEmbeddedContent(string $link): string
    {
        $embedLibrary = new Embed;
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

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new AttributesExtension);
        $environment->addExtension(new EmbedExtension);

        $this->converter = new MarkdownConverter($environment);

        return Cache::rememberForever(sha1($link), fn () => $this->converter->convert($link)->getContent());
    }
}
