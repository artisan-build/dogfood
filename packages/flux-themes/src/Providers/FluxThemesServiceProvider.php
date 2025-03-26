<?php

namespace ArtisanBuild\FluxThemes\Providers;

use ArtisanBuild\FluxThemes\Commands\InstallCommand;
use ArtisanBuild\FluxThemes\Commands\SetThemeCommand;
use ArtisanBuild\FluxThemes\Contracts\LoadsHeaderLeftNavbarItems;
use ArtisanBuild\FluxThemes\Contracts\LoadsHeaderRightNavbarItems;
use ArtisanBuild\FluxThemes\Livewire\FooterComponent;
use ArtisanBuild\FluxThemes\Livewire\HeaderLeftNavbarComponent;
use ArtisanBuild\FluxThemes\Livewire\HeaderRightNavbarComponent;
use ArtisanBuild\FluxThemes\Livewire\SearchComponent;
use ArtisanBuild\FluxThemes\Theme\LoadHeaderLeftNavbarItems;
use ArtisanBuild\FluxThemes\Theme\LoadHeaderRightNavbarItems;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Override;

class FluxThemesServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/flux-themes.php', 'flux-themes');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'flux-themes');
        $this->commands([
            InstallCommand::class,
            SetThemeCommand::class,
        ]);

        $this->app->bind(LoadsHeaderRightNavbarItems::class, config('flux-themes.loads_header_items.right'));
        $this->app->bind(LoadsHeaderLeftNavbarItems::class, config('flux-themes.loads_header_items.left'));
    }

    public function boot(): void
    {

        Livewire::component('ft:search', SearchComponent::class);
        Livewire::component('ft:header-left-navbar', HeaderLeftNavbarComponent::class);
        Livewire::component('ft:header-right-navbar', HeaderRightNavbarComponent::class);
        Livewire::component('ft:footer', FooterComponent::class);

        $this->publishes([
            __DIR__.'/../../config/flux-themes.php' => config_path('flux-themes.php'),
        ], 'flux-themes');

    }
}
