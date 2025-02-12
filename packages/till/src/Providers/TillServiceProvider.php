<?php

namespace ArtisanBuild\Till\Providers;

use ArtisanBuild\Till\Commands\CleanUpAfterTestingCommand;
use ArtisanBuild\Till\Commands\CreatePlanCommand;
use ArtisanBuild\Till\Commands\InstallCommand;
use ArtisanBuild\Till\Livewire\PricingSectionComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Override;

class TillServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/till.php', 'till');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'till');
        $this->loadRoutesFrom(__DIR__.'/../../routes/till.php');

    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CleanUpAfterTestingCommand::class,
                CreatePlanCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../../config/till.php' => config_path('till.php'),
        ], 'till');

        Livewire::component('till:pricing-section', PricingSectionComponent::class);
    }
}
