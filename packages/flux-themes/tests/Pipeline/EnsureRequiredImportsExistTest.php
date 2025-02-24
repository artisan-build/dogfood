<?php

use ArtisanBuild\FluxThemes\Pipeline\EnsureRequiredImportsExist;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Pipeline;

describe('ensuring the required imports exist in app.css', function (): void {
    it('adds the required imports in the correct order if the existing file is blank', function (): void {
        $theme = new ArtisanBuild\FluxThemes\Theme(css_file: __DIR__.'/../files/blank_app.css');

        $theme = Pipeline::send($theme)->through([
            EnsureRequiredImportsExist::class,
        ])->thenReturn();

        expect($theme->css)->toContain(implode("\n", ["@import 'tailwindcss';", "@import '../../vendor/livewire/flux/dist/flux.css';"]));
    })->skip();

    it('adds the flux import in the correct location in the Laravel 12 default file', function (): void {
        $theme = new ArtisanBuild\FluxThemes\Theme(css_file: __DIR__.'/../files/laravel_12_default_app.css');

        $theme = Pipeline::send($theme)->through([
            EnsureRequiredImportsExist::class,
        ])->thenReturn();

        expect($theme->css)->toContain(implode("\n", ["@import 'tailwindcss';", "@import '../../vendor/livewire/flux/dist/flux.css';"]))
            ->and($theme->css)->toBe(File::get(__DIR__.'/../files/laravel_default_with_imports_and_paths.css'));
    })->skip();

});
