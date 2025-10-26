<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeClient\Providers;

use ArtisanBuild\OpencodeClient\Livewire\OpencodeChat;
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
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'opencode-client');

        $this->publishes([
            __DIR__.'/../../config/opencode-client.php' => config_path('opencode-client.php'),
        ], 'opencode-client');

        Livewire::component('opencode-chat', OpencodeChat::class);

        Route::middleware('web')->get('/opencode-chat', OpencodeChat::class);
    }
}
