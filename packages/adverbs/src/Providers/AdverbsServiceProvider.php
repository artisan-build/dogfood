<?php

namespace ArtisanBuild\Adverbs\Providers;

use Illuminate\Support\ServiceProvider;

class AdverbsServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/adverbs.php', 'adverbs');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/adverbs.php' => config_path('adverbs.php'),
        ], 'adverbs');
    }
}
