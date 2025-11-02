<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller;

use ArtisanBuild\AgentOsInstaller\Commands\InstallCommand;
use ArtisanBuild\AgentOsInstaller\Commands\OptimizeClaudeReviewsCommand;
use Illuminate\Support\ServiceProvider;
use Override;

/**
 * Service provider for the Agent OS Installer package
 */
class AgentOsInstallerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services
     */
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/agent-os-installer.php',
            'agent-os-installer'
        );
    }

    /**
     * Bootstrap any application services
     */
    public function boot(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'agent-os-installer');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/agent-os-installer.php' => config_path('agent-os-installer.php'),
            ], 'agent-os-installer-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/agent-os-installer'),
            ], 'agent-os-installer-views');

            $this->commands([
                InstallCommand::class,
                OptimizeClaudeReviewsCommand::class,
            ]);
        }

        $this->registerViewerRoutes();
    }

    /**
     * Register web viewer routes if enabled
     */
    protected function registerViewerRoutes(): void
    {
        if (! config('agent-os-installer.viewer.enabled', true)) {
            return;
        }

        $prefix = config('agent-os-installer.viewer.route_prefix', 'agent-os');
        $middleware = config('agent-os-installer.viewer.middleware', ['web']);

        \Illuminate\Support\Facades\Route::middleware($middleware)
            ->prefix($prefix)
            ->group(function () {
                // Index route - displays Product folder or README based on config
                \Illuminate\Support\Facades\Route::get('/', function () {
                    return view('agent-os-installer::viewer');
                })->name('agent-os.index');

                // View specific file/spec route
                \Illuminate\Support\Facades\Route::get('/{path}', function (string $path) {
                    return view('agent-os-installer::viewer', ['path' => $path]);
                })->where('path', '.*')->name('agent-os.view');
            });
    }
}
