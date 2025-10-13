<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller;

use ArtisanBuild\AgentOsInstaller\Commands\InstallCommand;
use ArtisanBuild\AgentOsInstaller\Commands\OptimizeClaudeReviewsCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Agent OS Installer package
 */
class AgentOsInstallerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services
     */
    #[\Override]
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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/agent-os-installer.php' => config_path('agent-os-installer.php'),
            ], 'agent-os-installer-config');

            $this->commands([
                InstallCommand::class,
                OptimizeClaudeReviewsCommand::class,
            ]);
        }
    }
}
