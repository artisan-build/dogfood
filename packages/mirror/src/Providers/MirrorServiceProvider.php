<?php

namespace ArtisanBuild\Mirror\Providers;

use ArtisanBuild\Mirror\Services\ReflectionService;
use Illuminate\Support\ServiceProvider;

class MirrorServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/mirror.php', 'mirror');

        $this->app->bind('mirror', fn () => new ReflectionService($this->app));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/mirror.php' => config_path('mirror.php'),
        ], 'mirror');
    }
}
