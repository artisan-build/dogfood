<?php

use ArtisanBuild\FluxThemes\Enums\Colors;
use Illuminate\Foundation\Testing\WithConsoleEvents;
use Illuminate\Support\Facades\File;

uses(WithConsoleEvents::class);

beforeEach(function (): void {
    File::put(__DIR__.'/files/tailwind.config.js', File::get(__DIR__.'/files/tailwind.config.original.js'));
    File::put(__DIR__.'/files/app.css', File::get(__DIR__.'/files/app.css.original'));
});

it('sets up the a theme if a color is passed', function (): void {
    $command = test()->artisan('flux-themes:set', [
        'color' => 'red',
        '--css_file' => __DIR__.'/files/app.css',
        '--tailwind_config' => __DIR__.'/files/tailwind.config.js',
    ]);
    // expect(File::get(__DIR__.'/files/app.css'))->toBeIgnoringWhitespace(File::get(__DIR__.'/files/red_theme.css'));
    $command->expectsOutput('Writing the app.css file')
        ->expectsOutput('Writing to tailwind.config.php file')
        ->assertExitCode(0);
});

it('sets up a theme when a color is selected', function (): void {
    $command = test()->artisan('flux-themes:set', [
        '--css_file' => __DIR__.'/files/app.css',
        '--tailwind_config' => __DIR__.'/files/tailwind.config.js',
    ]);
    // expect(File::get(__DIR__.'/files/app.css'))->toBeIgnoringWhitespace(File::get(__DIR__.'/files/red_theme.css'));
    $command->expectsSearch('What color scheme do you want to use for this project?',
        search: 'red', answers: collect(Colors::cases())->map(fn ($color) => $color->value)->toArray(), answer: 'red')
        ->expectsOutput('Writing the app.css file')
        ->expectsOutput('Writing to tailwind.config.php file')
        ->assertExitCode(0);
});

it('fails if the color is not defined', function (): void {
    $command = test()->artisan('flux-themes:set', [
        'color' => 'banana',
        '--css_file' => __DIR__.'/files/app.css',
        '--tailwind_config' => __DIR__.'/files/tailwind.config.js',
    ]);

    $command->expectsOutput('banana is not a configured color')
        ->assertExitCode(1);
});
