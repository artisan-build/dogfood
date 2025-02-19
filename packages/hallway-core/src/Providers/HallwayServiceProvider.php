<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Providers;

use ArtisanBuild\Hallway\TextRendering\Contracts\ConvertsMarkdownToHtml;
use ArtisanBuild\Hallway\TextRendering\Contracts\HandlesEmbeddableLinks;
use ArtisanBuild\Hallway\TextRendering\Markdown\ConvertMarkdownToFluxUI;
use ArtisanBuild\Hallway\TextRendering\Markdown\CopyEmbeddableTagsToNewLines;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class HallwayServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->bindIf(ConvertsMarkdownToHtml::class, ConvertMarkdownToFluxUI::class);
        $this->app->bindIf(HandlesEmbeddableLinks::class, CopyEmbeddableTagsToNewLines::class);
    }

    public function boot(): void
    {
        Arr::macro('addUniqueToList', function (array $array, mixed $value): array {
            $array[] = $value;

            return array_unique($array);
        });

        Arr::macro('removeFromList', fn (array $array, mixed $value): array => collect($array)->filter(fn ($val) => $val !== $value)->toArray());
        Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components', 'hallway');
    }
}
