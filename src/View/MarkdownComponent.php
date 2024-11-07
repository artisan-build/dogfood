<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\View;

use Embed\Embed;
use Illuminate\View\Component;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownComponent extends Component
{
    private MarkdownConverter $converter;

    public function __construct(
        public string $content,
        public string $html = '',
    ) {
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



    }

    public function render()
    {
        $this->content = $this->converter->convert($this->content)->getContent();
        return view('hallway-flux::components.markdown-component');
    }
}
