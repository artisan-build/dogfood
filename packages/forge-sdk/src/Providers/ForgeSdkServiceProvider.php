<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Providers;

use Illuminate\Support\ServiceProvider;
use Override;

class ForgeSdkServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/forge-sdk.php', 'forge-sdk');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/forge-sdk.php' => config_path('forge-sdk.php'),
        ], 'forge-sdk');
    }
}
