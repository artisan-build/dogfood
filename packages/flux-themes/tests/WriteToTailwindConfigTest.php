<?php

use ArtisanBuild\FluxThemes\Actions\WriteToTailwindConfig;
use ArtisanBuild\FluxThemes\Enums\Grays;
use ArtisanBuild\FluxThemes\Theme;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    File::put(__DIR__.'/files/tailwind.config.js', File::get(__DIR__.'/files/tailwind.config.original.js'));
});

it('writes the gray to a config if nothing exists yet', function (): void {
    $theme = new Theme(tailwind_config: __DIR__.'/files/tailwind.config.js');
    $theme->gray = Grays::Gray;
    app(WriteToTailwindConfig::class)($theme);
    expect(File::get(__DIR__.'/files/tailwind.config.js'))->toBeIgnoringWhitespace(File::get(__DIR__.'/files/tailwind.gray.js'));
});

it('replaces the gray in the config if one already exists', function (): void {
    $theme = new Theme(tailwind_config: __DIR__.'/files/tailwind.config.js');
    $theme->gray = Grays::Slate;
    app(WriteToTailwindConfig::class)($theme);
    expect(File::get(__DIR__.'/files/tailwind.config.js'))->toBeIgnoringWhitespace(File::get(__DIR__.'/files/tailwind.slate.js'));
    $theme = new Theme(tailwind_config: __DIR__.'/files/tailwind.config.js');
    $theme->gray = Grays::Gray;
    app(WriteToTailwindConfig::class)($theme);
    expect(File::get(__DIR__.'/files/tailwind.config.js'))->toBeIgnoringWhitespace(File::get(__DIR__.'/files/tailwind.gray.js'));
});

it('removes the gray in the config if the default is selected', function (): void {
    $theme = new Theme(tailwind_config: __DIR__.'/files/tailwind.config.js');
    $theme->gray = Grays::Slate;
    app(WriteToTailwindConfig::class)($theme);
    expect(File::get(__DIR__.'/files/tailwind.config.js'))->toBeIgnoringWhitespace(File::get(__DIR__.'/files/tailwind.slate.js'));
    $theme = new Theme(tailwind_config: __DIR__.'/files/tailwind.config.js');
    $theme->gray = Grays::Zinc;
    app(WriteToTailwindConfig::class)($theme);
    expect(File::get(__DIR__.'/files/tailwind.config.js'))->toBeIgnoringWhitespace(File::get(__DIR__.'/files/tailwind.zinc.js'));
});
