<?php

namespace ArtisanBuild\GH\Commands;

class Auth extends BaseCommand
{
    protected string $config_key = 'gh.default_options.auth';

    public function login(?string $hostname = null, ?string $scopes = null, bool $web = false): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'auth',
            'login',
            $hostname ? '--hostname '.escapeshellarg($hostname) : null,
            $scopes ? '--scopes '.escapeshellarg($scopes) : null,
            $web ? '--web' : null,
            $this->getOptions('login'),
        ]));

        return $this->executeCommand($command);
    }

    public function logout(?string $hostname = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'auth',
            'logout',
            $hostname ? '--hostname '.escapeshellarg($hostname) : null,
            $this->getOptions('logout'),
        ]));

        return $this->executeCommand($command);
    }

    public function status(?string $hostname = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'auth',
            'status',
            $hostname ? '--hostname '.escapeshellarg($hostname) : null,
            $this->getOptions('status'),
        ]));

        return $this->executeCommand($command);
    }

    public function token(): string
    {
        $command = implode(' ', [
            'gh',
            'auth',
            'token',
            $this->getOptions('token'),
        ]);

        return $this->executeCommand($command);
    }
}
