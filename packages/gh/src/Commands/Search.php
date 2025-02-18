<?php

namespace ArtisanBuild\GH\Commands;

class Search extends BaseCommand
{
    protected string $config_key = 'gh.default_options.search';

    public function __construct() {}

    public function repos(string $query): string
    {
        $command = implode(' ', [
            'gh',
            'search',
            'repos',
            '--query',
            escapeshellarg($query),
            $this->getOptions('repos'),
        ]);

        return $this->executeCommand($command);
    }

    public function issues(string $query): string
    {
        $command = implode(' ', [
            'gh',
            'search',
            'issues',
            '--query',
            escapeshellarg($query),
            $this->getOptions('issues'),
        ]);

        return $this->executeCommand($command);
    }

    public function prs(string $query): string
    {
        $command = implode(' ', [
            'gh',
            'search',
            'prs',
            '--query',
            escapeshellarg($query),
            $this->getOptions('prs'),
        ]);

        return $this->executeCommand($command);
    }

    public function commits(string $query): string
    {
        $command = implode(' ', [
            'gh',
            'search',
            'commits',
            '--query',
            escapeshellarg($query),
            $this->getOptions('commits'),
        ]);

        return $this->executeCommand($command);
    }

    public function code(string $query): string
    {
        $command = implode(' ', [
            'gh',
            'search',
            'code',
            '--query',
            escapeshellarg($query),
            $this->getOptions('code'),
        ]);

        return $this->executeCommand($command);
    }

    public function users(string $query): string
    {
        $command = implode(' ', [
            'gh',
            'search',
            'users',
            '--query',
            escapeshellarg($query),
            $this->getOptions('users'),
        ]);

        return $this->executeCommand($command);
    }
}
