<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Providers;

use ArtisanBuild\ForgeClient\Console\Commands\ActivateSslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateDatabaseCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateDatabaseUserCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateFirewallRuleCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateServerCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateSiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\CreateSslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DeploySiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyDatabaseCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyDatabaseUserCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyFirewallRuleCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroyServerCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroySiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DestroySslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\DisableQuickDeployCommand;
use ArtisanBuild\ForgeClient\Console\Commands\EnableQuickDeployCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetDatabaseCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetDatabaseUserCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetDeploymentCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetEnvironmentCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetFirewallRuleCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetOrganizationCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetServerCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetSiteCommand;
use ArtisanBuild\ForgeClient\Console\Commands\GetSslCertificateCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListBackgroundProcessesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListDatabasesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListDatabaseUsersCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListDeploymentsCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListFirewallRulesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListOrganizationsCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListProviderRegionsCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListProvidersCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListProviderSizesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListServerCredentialsCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListServersCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListSitesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\ListSslCertificatesCommand;
use ArtisanBuild\ForgeClient\Console\Commands\RebootServerCommand;
use ArtisanBuild\ForgeClient\Console\Commands\RestartBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\TriggerDeploymentCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateBackgroundProcessCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateDatabaseUserCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateDeploymentScriptCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateEnvironmentCommand;
use ArtisanBuild\ForgeClient\Console\Commands\UpdateSiteCommand;
use ArtisanBuild\ForgeClient\ForgeClient;
use Illuminate\Support\ServiceProvider;
use Override;
use Saloon\Http\Auth\TokenAuthenticator;

class ForgeClientServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/forge-client.php', 'forge-client');

        $this->app->singleton(ForgeClient::class, function ($app): ForgeClient {
            $sdk = new ForgeClient;

            $token = config('forge-client.api_token');

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
                __DIR__.'/../../config/forge-client.php' => config_path('forge-client.php'),
            ], 'forge-client-config');

            $this->commands([
                // Organization Commands
                ListOrganizationsCommand::class,
                GetOrganizationCommand::class,

                // Server Credential Commands
                ListServerCredentialsCommand::class,

                // Provider Commands
                ListProvidersCommand::class,
                ListProviderRegionsCommand::class,
                ListProviderSizesCommand::class,

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

                // Environment Commands
                GetEnvironmentCommand::class,
                UpdateEnvironmentCommand::class,

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
