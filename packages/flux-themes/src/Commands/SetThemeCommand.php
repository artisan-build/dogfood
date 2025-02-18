<?php

namespace ArtisanBuild\FluxThemes\Commands;

use ArtisanBuild\FluxThemes\Actions\WriteToAppCss;
use ArtisanBuild\FluxThemes\Actions\WriteToTailwindConfig;
use ArtisanBuild\FluxThemes\Enums\Colors;
use ArtisanBuild\FluxThemes\Theme;
use Illuminate\Console\Command;

use function Laravel\Prompts\search;

class SetThemeCommand extends Command
{
    protected $signature = 'flux-themes:set {color?} {--tailwind_config=} {--css_file=}';

    protected $description = 'Set up FluxUI and set a color scheme';

    public function handle(): int
    {
        $config = $this->option('tailwind_config') ?? base_path('tailwind.config.js');
        $css = $this->option('css_file') ?? resource_path('css/app.css');
        $color = $this->argument('color');

        if ($color === null) {
            $color = search(
                label: 'What color scheme do you want to use for this project?',
                options: fn () => collect(Colors::cases())->map(fn ($color) => $color->value)->toArray(),
            );
        }

        $theme_color = Colors::tryFrom($color);

        if (! $theme_color instanceof Colors) {
            $this->error("{$color} is not a configured color");

            return self::FAILURE;
        }

        $theme = new Theme(
            css_file: $css,
            tailwind_config: $config,
        );

        $theme = $theme_color->set($theme);

        $this->info('Writing the app.css file');
        app(WriteToAppCss::class)(theme: $theme);
        $this->info('Writing to tailwind.config.php file');
        app(WriteToTailwindConfig::class)(theme: $theme);

        return self::SUCCESS;
    }
}
