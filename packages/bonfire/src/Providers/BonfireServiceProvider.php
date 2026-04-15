<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Providers;

use Illuminate\Support\ServiceProvider;
use Override;

class BonfireServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/bonfire.php', 'bonfire');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/bonfire.php' => config_path('bonfire.php'),
        ], 'bonfire');
    }
}
