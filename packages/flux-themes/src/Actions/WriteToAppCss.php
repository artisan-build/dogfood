<?php

namespace ArtisanBuild\FluxThemes\Actions;

use ArtisanBuild\FluxThemes\Theme;
use Illuminate\Support\Facades\File;

class WriteToAppCss
{
    public function __invoke(Theme $theme): void
    {
        $css = File::get($theme->css_file);

        $properties = ['color_accent', 'color_accent_content', 'color_accent_foreground'];

        $new = "@layer base {\n    :root {\n";
        foreach ($properties as $key) {
            /** @var array $property */
            $property = $theme->$key;
            $new .= '        --'.str_replace('_', '-', $key).": var({$property['light']});\n";
        }
        $new .= "    }\n\n    .dark {\n";
        foreach ($properties as $key) {
            $property = $theme->$key;
            $new .= '        --'.str_replace('_', '-', $key).": var({$property['dark']});\n";
        }
        $new .= "    }\n}";

        // $css = preg_replace('/:root {.*?}/s', '', $css);
        // $css = preg_replace('/:dark {.*?}/s', '', $css);
        $css = preg_replace('/@layer base {.*?}\n}/s', '', $css);

        File::put($theme->css_file, implode("\n", [$css, $new]));

    }
}
