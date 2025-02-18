<?php

namespace ArtisanBuild\MarketingMailcoach\Providers;

use Illuminate\Support\ServiceProvider;

class MarketingMailcoachServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/marketing-mailcoach.php', 'marketing-mailcoach');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/marketing-mailcoach.php' => config_path('marketing-mailcoach.php'),
        ], 'marketing-mailcoach');
    }
}
