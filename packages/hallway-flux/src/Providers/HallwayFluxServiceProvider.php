<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Providers;

use ArtisanBuild\Adverbs\Support\StateSynth;
use ArtisanBuild\HallwayFlux\Actions\RedirectOnSuccess;
use ArtisanBuild\HallwayFlux\Livewire\Layout\DetectMemberTimezone;
use ArtisanBuild\HallwayFlux\Livewire\Layout\LogoutButton;
use ArtisanBuild\HallwayFlux\View\MarkdownComponent;
use ArtisanBuild\VerbsFlux\Contracts\RedirectsOnSuccess;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use Override;
use Spatie\StructureDiscoverer\Discover;

class HallwayFluxServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/hallway-flux.php', 'hallway-flux');
        $this->loadRoutesFrom(__DIR__.'/../../routes/hallway-flux-routes.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'hallway-flux');
        $this->app->bind(RedirectsOnSuccess::class, RedirectOnSuccess::class);
    }

    public function boot(): void
    {
        Livewire::propertySynthesizer(StateSynth::class);
        Livewire::component('logout-button', LogoutButton::class);
        Livewire::component('detect-member-timezone', DetectMemberTimezone::class);

        // Blade::aliasComponent('markdown', MarkdownComponent::class);

        // TODO: We should create a command to list all registered livewire components so we can see what these keys look like
        collect(Discover::in(__DIR__.'/../')->classes()->extending(Component::class)->get())
            ->each(fn ($class) => Livewire::component(Str::of($class)->headline()->slug()->toString(), $class));
    }
}
