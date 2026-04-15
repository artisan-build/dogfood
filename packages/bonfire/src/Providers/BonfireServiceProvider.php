<?php

declare(strict_types=1);

namespace ArtisanBuild\Bonfire\Providers;

use ArtisanBuild\Bonfire\BonfireManager;
use Illuminate\Support\ServiceProvider;
use Override;

class BonfireServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/bonfire.php', 'bonfire');

        $this->app->singleton(BonfireManager::class, fn (): BonfireManager => new BonfireManager);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        $this->publishes([
            __DIR__.'/../../config/bonfire.php' => config_path('bonfire.php'),
        ], 'bonfire-config');

        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'bonfire-migrations');
    }
}
