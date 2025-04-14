<?php

namespace ArtisanBuild\Turbulence\Providers;

use ArtisanBuild\Turbulence\Commands\GenerateCommand;
use ArtisanBuild\Turbulence\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;
use Override;

class TurbulenceServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/turbulence.php', 'turbulence');
    }

    public function boot(): void
    {
        $this->commands([
            InstallCommand::class,
            GenerateCommand::class,
        ]);
        $this->publishes([
            __DIR__.'/../../config/turbulence.php' => config_path('turbulence.php'),
        ], 'turbulence');
    }
}
