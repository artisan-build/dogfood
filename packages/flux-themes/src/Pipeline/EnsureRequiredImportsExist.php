<?php

namespace ArtisanBuild\FluxThemes\Pipeline;

use ArtisanBuild\FluxThemes\Theme;
use Closure;
use Illuminate\Support\Str;

class EnsureRequiredImportsExist
{
    public function __invoke(Theme $theme, Closure $next): Theme
    {
        if (! Str::of($theme->css)->contains("@import 'tailwindcss';")) {
            $theme->css = implode("\n", ["@import 'tailwindcss';", $theme->css])."\n";
        }

        if (! Str::of($theme->css)->contains("@import '../../vendor/livewire/flux/dist/flux.css';")) {
            $theme->css = str_replace("@import 'tailwindcss';", implode("\n", [
                "@import 'tailwindcss';",
                "@import '../../vendor/livewire/flux/dist/flux.css';",
            ]), $theme->css)."\n";
        }

        return $next($theme);
    }
}
