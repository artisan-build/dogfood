<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\View\Components;

use ArtisanBuild\EmbeddableLinks\Services\EmbedManager;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Embed extends Component
{
    public function __construct(
        protected EmbedManager $manager,
        public string $url,
        public ?string $aspectRatio = null,
        public bool $cache = true,
    ) {}

    public function render(): View|string
    {
        $options = array_filter([
            'aspect_ratio' => $this->aspectRatio,
            'cache' => $this->cache,
        ]);

        return $this->manager->embed($this->url, $options);
    }
}
