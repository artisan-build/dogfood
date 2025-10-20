<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeSdk\Facades;

use ArtisanBuild\ForgeSdk\ForgeSdk;
use ArtisanBuild\ForgeSdk\Resource\BackgroundProcesses;
use ArtisanBuild\ForgeSdk\Resource\Commands;
use ArtisanBuild\ForgeSdk\Resource\Databases;
use ArtisanBuild\ForgeSdk\Resource\Deployments;
use ArtisanBuild\ForgeSdk\Resource\FirewallRules;
use ArtisanBuild\ForgeSdk\Resource\Integrations;
use ArtisanBuild\ForgeSdk\Resource\Logs;
use ArtisanBuild\ForgeSdk\Resource\Monitors;
use ArtisanBuild\ForgeSdk\Resource\Nginx;
use ArtisanBuild\ForgeSdk\Resource\Organizations;
use ArtisanBuild\ForgeSdk\Resource\Providers;
use ArtisanBuild\ForgeSdk\Resource\Recipes;
use ArtisanBuild\ForgeSdk\Resource\RedirectRules;
use ArtisanBuild\ForgeSdk\Resource\Roles;
use ArtisanBuild\ForgeSdk\Resource\ScheduledJobs;
use ArtisanBuild\ForgeSdk\Resource\SecurityRules;
use ArtisanBuild\ForgeSdk\Resource\ServerCredentials;
use ArtisanBuild\ForgeSdk\Resource\Servers;
use ArtisanBuild\ForgeSdk\Resource\Sites;
use ArtisanBuild\ForgeSdk\Resource\SshKeys;
use ArtisanBuild\ForgeSdk\Resource\Teams;
use ArtisanBuild\ForgeSdk\Resource\User;
use Illuminate\Support\Facades\Facade;

/**
 * Laravel Forge SDK Facade
 *
 * @method static BackgroundProcesses backgroundProcesses()
 * @method static Commands commands()
 * @method static Databases databases()
 * @method static Deployments deployments()
 * @method static FirewallRules firewallRules()
 * @method static Integrations integrations()
 * @method static Logs logs()
 * @method static Monitors monitors()
 * @method static Nginx nginx()
 * @method static Organizations organizations()
 * @method static Providers providers()
 * @method static Recipes recipes()
 * @method static RedirectRules redirectRules()
 * @method static Roles roles()
 * @method static ScheduledJobs scheduledJobs()
 * @method static SecurityRules securityRules()
 * @method static ServerCredentials serverCredentials()
 * @method static Servers servers()
 * @method static Sites sites()
 * @method static SshKeys sshKeys()
 * @method static Teams teams()
 * @method static User user()
 *
 * @see ForgeSdk
 */
class Forge extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return ForgeSdk::class;
    }
}
