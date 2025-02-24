<?php

namespace ArtisanBuild\FluxThemes;

use ArtisanBuild\FluxThemes\Enums\Grays;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

    public array $configuration;

    public string $css;

    public function __construct(public ?string $css_file = null)
    {
        $this->css_file ??= resource_path('css/app.css');
        $this->css = File::get($this->css_file);
        $this->getCurrentConfiguration();
    }

    public function getCurrentConfiguration()
    {
        $css = [
            'imports' => [],
            'sources' => [],
            'grays' => [],
            'colors' => [],
        ];
        $block = null;
        foreach (File::lines($this->css_file) as $line) {
            if (Str::of($line)->trim()->startsWith(':root')) {
                $block = 'light';
            }
            if (Str::of($line)->trim()->startsWith('.dark')) {
                $block = 'dark';
            }
            if (Str::of($line)->trim()->startsWith('@import')) {
                $css['imports'][] = Str::of($line)->trim()->toString();
            }
            if (Str::of($line)->trim()->startsWith('@source')) {
                $css['sources'][] = Str::of($line)->trim()->toString();
            }
            if (Str::of($line)->trim()->startsWith('--color-zinc')) {
                $css['grays'][current(explode(':', Str::of($line)->trim()))] = Str::of($line)->trim()->toString();
            }
            if ($block !== null && Str::of($line)->trim()->startsWith('--color-accent')) {
                $css['colors'][$block][current(explode(':', Str::of($line)->trim()->toString()))] = Str::of($line)->trim()->toString();
            }

        }
        $this->configuration = $css;
    }
}
