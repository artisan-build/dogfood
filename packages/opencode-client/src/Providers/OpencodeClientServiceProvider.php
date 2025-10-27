<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeClient\Providers;

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
use ArtisanBuild\OpencodeClient\Livewire\OpencodeExplorer;
use ArtisanBuild\OpencodeClient\Livewire\OpencodeRemote;
use ArtisanBuild\OpencodeClient\Services\OpencodeService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Override;

class OpencodeClientServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/opencode-client.php', 'opencode-client');

        // Register OpencodeService with configured base URL
        $this->app->singleton(OpencodeService::class, fn ($app) => new OpencodeService(
            baseUrl: config('opencode-client.base_url', 'http://127.0.0.1:64415')
        ));
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'opencode-client');

        $this->publishes([
            __DIR__.'/../../config/opencode-client.php' => config_path('opencode-client.php'),
        ], 'opencode-client');

        Livewire::component('opencode-chat', OpencodeChat::class);
        Livewire::component('opencode-explorer', OpencodeExplorer::class);
        Livewire::component('opencode-remote', OpencodeRemote::class);

        Route::middleware('web')->get('/opencode-chat', OpencodeChat::class);
    }
}
