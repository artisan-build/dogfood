<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Providers;

use Illuminate\Support\ServiceProvider;

class HallwayFluxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/hallway-flux.php', 'hallway-flux');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/hallway-flux-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'hallway-flux');
    }
}
