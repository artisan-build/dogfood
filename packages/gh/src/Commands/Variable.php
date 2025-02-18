<?php

namespace ArtisanBuild\GH\Commands;

class Variable extends BaseCommand
{
    protected string $config_key = 'gh.default_options.variable';

    public function __construct(
        public ?string $repository = null,
    ) {}

    public function repository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'variable',
            'list',
            '--repo',
            $this->repository,
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function set(string $name, string $value): string
    {
        $command = implode(' ', [
            'gh',
            'variable',
            'set',
            $name,
            '--body',
            escapeshellarg($value),
            '--repo',
            $this->repository,
            $this->getOptions('set'),
        ]);

        return $this->executeCommand($command);
    }

    public function get(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'variable',
            'get',
            $name,
            '--repo',
            $this->repository,
            $this->getOptions('get'),
        ]);

        return $this->executeCommand($command);
    }

    public function delete(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'variable',
            'delete',
            $name,
            '--repo',
            $this->repository,
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }
}
