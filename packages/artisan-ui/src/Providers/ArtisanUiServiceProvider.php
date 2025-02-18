<?php

namespace ArtisanBuild\ArtisanUi\Providers;

use Illuminate\Support\ServiceProvider;
use Override;

class ArtisanUiServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/artisan-ui.php', 'artisan-ui');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'artisan-ui');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/artisan-ui.php' => config_path('artisan-ui.php'),
        ], 'artisan-ui');
    }
}
