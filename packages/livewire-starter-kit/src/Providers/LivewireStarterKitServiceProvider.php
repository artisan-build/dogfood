<?php

namespace ArtisanBuild\LivewireStarterKit\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Volt\Volt;
use Override;

class LivewireStarterKitServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/livewire-starter-kit.php', 'livewire-starter-kit');

        // Load views with both empty namespace and 'livewire-starter-kit' namespace
        $this->loadViewsFrom(__DIR__.'/../../resources/views', '');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'livewire-starter-kit');

        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        // Register anonymous Blade components
        Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components');

        // Register components with prefix for dot notation access
        Blade::componentNamespace('ArtisanBuild\\LivewireStarterKit\\View\\Components', '');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/livewire-starter-kit.php' => config_path('livewire-starter-kit.php'),
        ], 'livewire-starter-kit');

        // Also publish views if needed
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/livewire-starter-kit'),
        ], 'livewire-starter-kit-views');

        // Register specific aliases for layouts
        // Blade::aliasComponent('components.layouts.auth', 'layouts.auth');
        // Blade::aliasComponent('livewire-starter-kit::components.layouts.auth', 'components.layouts.auth');

        // Set up default layout configurations for Livewire
        config(['livewire.layout' => 'components.layouts.app']);
        config(['livewire.layouts.app' => 'components.layouts.app']);
        config(['livewire.layouts.auth' => 'components.layouts.auth']);

        Volt::mount([
            config('livewire.view_path', resource_path('views/livewire')),
            resource_path('views/pages'),
            __DIR__.'/../../resources/views/livewire',
        ]);
    }
}
