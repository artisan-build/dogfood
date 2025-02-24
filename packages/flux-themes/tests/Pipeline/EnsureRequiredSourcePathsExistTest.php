<?php

use ArtisanBuild\FluxThemes\Pipeline\EnsureRequiredImportsExist;
use ArtisanBuild\FluxThemes\Pipeline\EnsureRequiredSourcePathsExist;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Pipeline;

beforeEach(fn () => Config::set('flux-themes.vendor_directory', __DIR__.'/../files/vendor/artisan-build'));

describe('ensure that all source paths required for Tailwind tree shaking exist', function (): void {
    it('adds them all if the file does not include any', function (): void {
        $theme = new ArtisanBuild\FluxThemes\Theme(css_file: __DIR__.'/../files/blank_app.css');

        $theme = Pipeline::send($theme)
            ->through([
                EnsureRequiredImportsExist::class, // Only doing this so we know where to place the imports if a previous file is blank
                EnsureRequiredSourcePathsExist::class,
            ])
            ->thenReturn();

        expect($theme->css)->toBe(File::get(__DIR__.'/../files/blank_with_imports_and_sources.css'));

    });

    it('adds the missing paths if the file already has some', function (): void {
        $theme = new ArtisanBuild\FluxThemes\Theme(css_file: __DIR__.'/../files/laravel_12_default_app.css');

        $theme = Pipeline::send($theme)
            ->through([
                EnsureRequiredImportsExist::class, // Only doing this so we know where to place the imports if a previous file is blank
                EnsureRequiredSourcePathsExist::class,
            ])
            ->thenReturn();

        expect($theme->css)->toBeIgnoringWhitespace(File::get(__DIR__.'/../files/laravel_default_with_imports_and_paths.css'));

    })->skip();
});
