<?php

namespace ArtisanBuild\GH\Commands;

class Secret extends BaseCommand
{
    protected string $config_key = 'gh.default_options.secret';

    public function __construct(
        public ?string $repository = null,
    ) {}

    public function repository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function list(?string $env = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'secret',
            'list',
            '--repo',
            $this->repository,
            $env ? '--env '.escapeshellarg($env) : null,
            $this->getOptions('list'),
        ]));

        return $this->executeCommand($command);
    }

    public function set(string $name, string $value, ?string $env = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'secret',
            'set',
            $name,
            '--body',
            escapeshellarg($value),
            '--repo',
            $this->repository,
            $env ? '--env '.escapeshellarg($env) : null,
            $this->getOptions('set'),
        ]));

        return $this->executeCommand($command);
    }

    public function delete(string $name, ?string $env = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'secret',
            'delete',
            $name,
            '--repo',
            $this->repository,
            $env ? '--env '.escapeshellarg($env) : null,
            $this->getOptions('delete'),
        ]));

        return $this->executeCommand($command);
    }
}
