<?php

namespace ArtisanBuild\GH\Providers;

use ArtisanBuild\GH\Commands\Sandbox;
use Illuminate\Support\ServiceProvider;

class GHServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/gh.php', 'gh');
    }

    public function boot(): void
    {
        $this->commands([
            Sandbox::class,
        ]);
    }
}
