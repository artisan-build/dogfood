<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\View;

use Illuminate\View\Component;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownComponent extends Component
{
    private MarkdownConverter $converter;

    public function __construct(
        public string $content,
        public string $html = '',
    ) {
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new AttributesExtension());

        $this->converter = new MarkdownConverter($environment);



    }

    public function render()
    {
        $this->content = $this->converter->convert($this->content);
        return view('hallway-flux::components.markdown-component');
    }
}
