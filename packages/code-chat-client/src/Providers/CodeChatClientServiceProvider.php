<?php

declare(strict_types=1);

namespace ArtisanBuild\CodeChatClient\Providers;

use ArtisanBuild\CodeChatClient\ChatManager;
use ArtisanBuild\CodeChatClient\Livewire\CodeChatComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CodeChatClientServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/code-chat-client.php',
            'code-chat-client'
        );

        $this->app->singleton(ChatManager::class, fn ($app) => new ChatManager($app));

        $this->app->alias(ChatManager::class, 'code-chat');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'code-chat-client');

        Livewire::component('code-chat', CodeChatComponent::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/code-chat-client.php' => config_path('code-chat-client.php'),
            ], 'code-chat-client-config');

            $this->publishes([
                __DIR__.'/../../resources/views' => resource_path('views/vendor/code-chat-client'),
            ], 'code-chat-client-views');
        }
    }
}
