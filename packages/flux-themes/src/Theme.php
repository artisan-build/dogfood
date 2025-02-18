<?php

namespace ArtisanBuild\FluxThemes;

use ArtisanBuild\FluxThemes\Enums\Grays;

class Theme
{
    public array $color_accent = [
        'dark' => '--color-zinc-800',
        'light' => '--color-white',
    ];

    public array $color_accent_content = [
        'dark' => '--color-zinc-800',
        'light' => '--color-white',
    ];

    public array $color_accent_foreground = [
        'dark' => '--color-white',
        'light' => '--color-zinc-800',
    ];

    public Grays $gray = Grays::Slate;

    public function __construct(public ?string $css_file = null, public ?string $tailwind_config = null)
    {
        $this->css_file ??= resource_path('css/app.css');
        $this->tailwind_config ??= base_path('tailwind.config.js');
    }
}
