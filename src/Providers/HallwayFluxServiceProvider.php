<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Providers;

use App\Livewire\Synthesizers\StateSynth;
use ArtisanBuild\HallwayFlux\Actions\RedirectOnSuccess;
use ArtisanBuild\HallwayFlux\Livewire\Layout\LogoutButton;
use ArtisanBuild\HallwayFlux\View\MarkdownComponent;
use ArtisanBuild\VerbsFlux\Contracts\RedirectsOnSuccess;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class HallwayFluxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/hallway-flux.php', 'hallway-flux');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/hallway-flux-routes.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'hallway-flux');
        $this->app->bind(RedirectsOnSuccess::class, RedirectOnSuccess::class);
    }

    public function boot(): void
    {
        Livewire::propertySynthesizer(StateSynth::class);
        Livewire::component('logout-button', LogoutButton::class);

        Blade::component('markdown', MarkdownComponent::class);
    }
}
