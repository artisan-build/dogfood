<?php

namespace ArtisanBuild\GH\Commands;

class Cache extends BaseCommand
{
    protected string $config_key = 'gh.default_options.cache';

    public function __construct(public ?string $repository = null) {}

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'cache',
            'list',
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function delete(?string $key = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'cache',
            'delete',
            $key,
            $this->getOptions('delete'),
        ]));

        return $this->executeCommand($command);
    }

    public function restore(string $key): string
    {
        $command = implode(' ', [
            'gh',
            'cache',
            'restore',
            $key,
            $this->getOptions('restore'),
        ]);

        return $this->executeCommand($command);
    }
}
