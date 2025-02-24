<?php

namespace ArtisanBuild\FluxThemes\Commands;

use ArtisanBuild\FluxThemes\Enums\Colors;
use ArtisanBuild\FluxThemes\Pipeline\EnsureRequiredImportsExist;
use ArtisanBuild\FluxThemes\Pipeline\EnsureRequiredSourcePathsExist;
use ArtisanBuild\FluxThemes\Theme;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Pipeline;
use Stripe\File;

use function Laravel\Prompts\search;

class SetThemeCommand extends Command
{
    protected $signature = 'flux-themes:set {color?} {--tailwind_config=} {--css_file=}';

    protected $description = 'Set up FluxUI and set a color scheme';

    public function handle(): int
    {
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
        );

        $theme = $theme_color->set($theme);

        Pipeline::send($theme)->through([
            EnsureRequiredImportsExist::class,
            EnsureRequiredSourcePathsExist::class,
            // Handle the gray override
            // Set the highlight color variables
            // Remove double line breaks
            // Ensure one new line at the end of the contents
            // Write the contents to the app.css file
        ]);

        return self::SUCCESS;
    }
}
