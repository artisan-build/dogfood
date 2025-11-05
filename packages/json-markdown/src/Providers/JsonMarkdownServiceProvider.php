<?php

declare(strict_types=1);

namespace ArtisanBuild\JsonMarkdown\Providers;

use ArtisanBuild\JsonMarkdown\MarkdownDirectory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Override;

class JsonMarkdownServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/json-markdown.php', 'json-markdown');

        $this->app->singleton(MarkdownDirectory::class, fn ($app) => new MarkdownDirectory($app->make(Filesystem::class)));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/json-markdown.php' => config_path('json-markdown.php'),
        ], 'json-markdown');
    }
}
