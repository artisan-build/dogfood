<?php

declare(strict_types=1);

namespace ArtisanBuild\ForgeClient\Facades;

use ArtisanBuild\ForgeClient\ForgeClient;
use ArtisanBuild\ForgeClient\Resource\BackgroundProcesses;
use ArtisanBuild\ForgeClient\Resource\Commands;
use ArtisanBuild\ForgeClient\Resource\Databases;
use ArtisanBuild\ForgeClient\Resource\Deployments;
use ArtisanBuild\ForgeClient\Resource\FirewallRules;
use ArtisanBuild\ForgeClient\Resource\Integrations;
use ArtisanBuild\ForgeClient\Resource\Logs;
use ArtisanBuild\ForgeClient\Resource\Monitors;
use ArtisanBuild\ForgeClient\Resource\Nginx;
use ArtisanBuild\ForgeClient\Resource\Organizations;
use ArtisanBuild\ForgeClient\Resource\Providers;
use ArtisanBuild\ForgeClient\Resource\Recipes;
use ArtisanBuild\ForgeClient\Resource\RedirectRules;
use ArtisanBuild\ForgeClient\Resource\Roles;
use ArtisanBuild\ForgeClient\Resource\ScheduledJobs;
use ArtisanBuild\ForgeClient\Resource\SecurityRules;
use ArtisanBuild\ForgeClient\Resource\ServerCredentials;
use ArtisanBuild\ForgeClient\Resource\Servers;
use ArtisanBuild\ForgeClient\Resource\Sites;
use ArtisanBuild\ForgeClient\Resource\SshKeys;
use ArtisanBuild\ForgeClient\Resource\Teams;
use ArtisanBuild\ForgeClient\Resource\User;
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
 * @see ForgeClient
 */
class Forge extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return ForgeClient::class;
    }
}
