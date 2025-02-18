<?php

namespace ArtisanBuild\Packagist\Providers;

use Illuminate\Support\ServiceProvider;

class PackagistServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/packagist.php', 'packagist');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/packagist.php' => config_path('packagist.php'),
        ], 'packagist');
    }
}
