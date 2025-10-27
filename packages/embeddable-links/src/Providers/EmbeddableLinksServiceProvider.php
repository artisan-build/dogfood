<?php

declare(strict_types=1);

namespace ArtisanBuild\EmbeddableLinks\Providers;

use ArtisanBuild\EmbeddableLinks\Services\CacheManager;
use ArtisanBuild\EmbeddableLinks\Services\EmbedManager;
use ArtisanBuild\EmbeddableLinks\Services\MetadataFetcher;
use ArtisanBuild\EmbeddableLinks\Services\UrlParser;
use Illuminate\Support\ServiceProvider;
use Override;

class EmbeddableLinksServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/embeddable-links.php', 'embeddable-links');

        $this->app->singleton(UrlParser::class);
        $this->app->singleton(CacheManager::class);
        $this->app->singleton(MetadataFetcher::class);
        $this->app->singleton(EmbedManager::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/embeddable-links.php' => config_path('embeddable-links.php'),
        ], 'embeddable-links-config');

        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/embeddable-links'),
        ], 'embeddable-links-views');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'embeddable-links');

        $this->loadViewComponentsAs('embeddable-links', [
            \ArtisanBuild\EmbeddableLinks\View\Components\Embed::class,
        ]);

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }
}
