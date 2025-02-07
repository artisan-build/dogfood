<?php

namespace ArtisanBuild\TillStripe\Providers;

use Illuminate\Support\ServiceProvider;

class TillStripeServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/till-stripe.php', 'till-stripe');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/till-stripe.php' => config_path('till-stripe.php'),
        ], 'till-stripe');
    }
}
