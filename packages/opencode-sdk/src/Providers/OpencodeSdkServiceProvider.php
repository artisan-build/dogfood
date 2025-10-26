<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\Providers;

use ArtisanBuild\OpencodeSdk\OpenCode\OpenCode;
use Illuminate\Support\ServiceProvider;
use Override;

class OpencodeSdkServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/opencode-sdk.php', 'opencode-sdk');

        $this->app->singleton(OpenCode::class, function ($app) {
            $config = $app['config']['opencode-sdk'];

            $connector = new OpenCode(
                baseUrl: $config['base_url'],
            );

            // Configure timeout
            $connector->config()->add('timeout', $config['timeout']);

            // Configure retry logic
            if ($config['retry']['times'] > 0) {
                $connector->config()->add('retries', [
                    'times' => $config['retry']['times'],
                    'sleep' => $config['retry']['sleep'],
                ]);
            }

            return $connector;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/opencode-sdk.php' => config_path('opencode-sdk.php'),
        ], 'opencode-sdk');
    }
}
