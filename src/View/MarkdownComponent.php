<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\View;

use ArtisanBuild\Hallway\TextRendering\Contracts\ConvertsMarkdownToHtml;
use Illuminate\View\Component;

class MarkdownComponent extends Component
{
    public function __construct(public string $content) {}

    public function render()
    {
        $this->content = app(ConvertsMarkdownToHtml::class)($this->content);
        return view('hallway-flux::components.markdown-component');
    }
}
