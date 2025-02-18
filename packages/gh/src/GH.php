<?php

namespace ArtisanBuild\GH;

use ArtisanBuild\GH\Commands\Auth;
use ArtisanBuild\GH\Commands\Browse;
use ArtisanBuild\GH\Commands\Cache;
use ArtisanBuild\GH\Commands\Codespace;
use ArtisanBuild\GH\Commands\Config;
use ArtisanBuild\GH\Commands\Gist;
use ArtisanBuild\GH\Commands\GpgKey;
use ArtisanBuild\GH\Commands\Issue;
use ArtisanBuild\GH\Commands\Label;
use ArtisanBuild\GH\Commands\Org;
use ArtisanBuild\GH\Commands\Pr;
use ArtisanBuild\GH\Commands\Project;
use ArtisanBuild\GH\Commands\Release;
use ArtisanBuild\GH\Commands\Repo;
use ArtisanBuild\GH\Commands\Ruleset;
use ArtisanBuild\GH\Commands\Run;
use ArtisanBuild\GH\Commands\Search;
use ArtisanBuild\GH\Commands\Secret;
use ArtisanBuild\GH\Commands\Status;
use ArtisanBuild\GH\Commands\Variable;
use ArtisanBuild\GH\Commands\Workflow;

class GH
{
    public static function alias(): void
    {
        throw new \RuntimeException('We have not implemented this command because we do not currently have a use for it.');
    }

    public static function api(): void
    {
        throw new \RuntimeException('We have not implemented this command because we do not currently have a use for it.');
    }

    public static function attestation(): void
    {
        throw new \RuntimeException('We have not implemented this command because we do not currently have a use for it.');
    }

    public static function auth(): Auth
    {
        return new Auth;
    }

    public static function browse(?string $repository = null)
    {
        return new Browse($repository);
    }

    public static function cache(?string $repository = null): Cache
    {
        return new Cache($repository);
    }

    public static function codespace(?string $repository = null): Codespace
    {
        return new Codespace($repository);
    }

    public static function completion(): void
    {
        throw new \RuntimeException('We have not implemented this command because we do not currently have a use for it.');
    }

    public static function config(): Config
    {
        return new Config;
    }

    public static function extension(): void
    {
        throw new \RuntimeException('We have not implemented this command because we do not currently have a use for it.');
    }

    public static function gist(): Gist
    {
        return new Gist;
    }

    public static function gpgKey(): GpgKey
    {
        return new GpgKey;
    }

    public static function help(): void
    {
        throw new \RuntimeException('We have not implemented this command because we do not currently have a use for it.');
    }

    public static function issue(?string $repository = null): Issue
    {
        return new Issue($repository);
    }

    public static function label(?string $repository = null): Label
    {
        return new Label($repository);
    }

    public static function org(?string $organization = null): Org
    {
        return new Org($organization);
    }

    public static function pr(?string $repository): Pr
    {
        return new Pr($repository);
    }

    public static function project(?string $project = null): Project
    {
        return new Project($project);
    }

    public static function release(?string $repository = null): Release
    {
        return new Release($repository);
    }

    public static function repo(?string $repository = null): Repo
    {
        return new Repo($repository);
    }

    public static function ruleset(?string $repository = null): Ruleset
    {
        return new Ruleset($repository);
    }

    public static function run(?string $repository = null): Run
    {
        return new Run($repository);
    }

    public static function search(): Search
    {
        return new Search;
    }

    public static function secret(?string $repository): Secret
    {
        return new Secret($repository);
    }

    public static function sshKey(): void
    {
        throw new \RuntimeException('We have not implemented this command because we do not currently have a use for it.');
    }

    public static function status(): Status
    {
        return new Status;
    }

    public static function variable(?string $repository = null): Variable
    {
        return new Variable($repository);
    }

    public static function workflow(?string $repository = null): Workflow
    {
        return new Workflow($repository);
    }
}
