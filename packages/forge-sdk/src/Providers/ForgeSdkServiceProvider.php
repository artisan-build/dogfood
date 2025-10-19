<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Providers;

use ArtisanBuild\ForgeSdk\Console\Commands\ActivateSslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateDatabaseCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateFirewallRuleCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateServerCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateSiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\CreateSslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DeploySiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyDatabaseCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyFirewallRuleCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroyServerCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroySiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DestroySslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\DisableQuickDeployCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\EnableQuickDeployCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetDatabaseCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetDeploymentCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetFirewallRuleCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetOrganizationCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetServerCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetSiteCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\GetSslCertificateCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListBackgroundProcessesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListDatabasesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListDatabaseUsersCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListDeploymentsCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListFirewallRulesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListOrganizationsCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListServersCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListSitesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\ListSslCertificatesCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\RebootServerCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\RestartBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\TriggerDeploymentCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateBackgroundProcessCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateDatabaseUserCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateDeploymentScriptCommand;
use ArtisanBuild\ForgeSdk\Console\Commands\UpdateSiteCommand;
use ArtisanBuild\ForgeSdk\ForgeSdk;
use Illuminate\Support\ServiceProvider;
use Override;
use Saloon\Http\Auth\TokenAuthenticator;

class ForgeSdkServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/forge-sdk.php', 'forge-sdk');

        $this->app->singleton(ForgeSdk::class, function ($app): ForgeSdk {
            $sdk = new ForgeSdk;

            $token = config('forge-sdk.api_token');

            if ($token) {
                $sdk->authenticate(new TokenAuthenticator($token, 'Bearer'));
            }

            return $sdk;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/forge-sdk.php' => config_path('forge-sdk.php'),
            ], 'forge-sdk-config');

            $this->commands([
                // Organization Commands
                ListOrganizationsCommand::class,
                GetOrganizationCommand::class,

                // Server Commands
                ListServersCommand::class,
                GetServerCommand::class,
                CreateServerCommand::class,
                DestroyServerCommand::class,
                RebootServerCommand::class,

                // Site Commands
                ListSitesCommand::class,
                GetSiteCommand::class,
                CreateSiteCommand::class,
                UpdateSiteCommand::class,
                DestroySiteCommand::class,
                DeploySiteCommand::class,
                EnableQuickDeployCommand::class,
                DisableQuickDeployCommand::class,

                // Deployment Commands
                ListDeploymentsCommand::class,
                GetDeploymentCommand::class,
                TriggerDeploymentCommand::class,
                UpdateDeploymentScriptCommand::class,

                // Database Commands
                ListDatabasesCommand::class,
                GetDatabaseCommand::class,
                CreateDatabaseCommand::class,
                DestroyDatabaseCommand::class,

                // Database User Commands
                ListDatabaseUsersCommand::class,
                GetDatabaseUserCommand::class,
                CreateDatabaseUserCommand::class,
                UpdateDatabaseUserCommand::class,
                DestroyDatabaseUserCommand::class,

                // Background Process Commands
                ListBackgroundProcessesCommand::class,
                GetBackgroundProcessCommand::class,
                CreateBackgroundProcessCommand::class,
                UpdateBackgroundProcessCommand::class,
                RestartBackgroundProcessCommand::class,
                DestroyBackgroundProcessCommand::class,

                // Firewall Rule Commands
                ListFirewallRulesCommand::class,
                GetFirewallRuleCommand::class,
                CreateFirewallRuleCommand::class,
                DestroyFirewallRuleCommand::class,

                // SSL Certificate Commands
                ListSslCertificatesCommand::class,
                GetSslCertificateCommand::class,
                CreateSslCertificateCommand::class,
                ActivateSslCertificateCommand::class,
                DestroySslCertificateCommand::class,
            ]);
        }
    }
}
