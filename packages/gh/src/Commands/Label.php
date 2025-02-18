<?php

namespace ArtisanBuild\GH\Commands;

class Label extends BaseCommand
{
    protected string $config_key = 'gh.default_options.label';

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
            'label',
            'list',
            '--repo',
            $this->repository,
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function create(string $name, ?string $description = null, ?string $color = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'label',
            'create',
            $name,
            '--repo',
            $this->repository,
            $description ? '--description '.escapeshellarg($description) : null,
            $color ? '--color '.escapeshellarg($color) : null,
            $this->getOptions('create'),
        ]));

        return $this->executeCommand($command);
    }

    public function edit(string $name, ?string $newName = null, ?string $description = null, ?string $color = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'label',
            'edit',
            $name,
            '--repo',
            $this->repository,
            $newName ? '--name '.escapeshellarg($newName) : null,
            $description ? '--description '.escapeshellarg($description) : null,
            $color ? '--color '.escapeshellarg($color) : null,
            $this->getOptions('edit'),
        ]));

        return $this->executeCommand($command);
    }

    public function delete(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'label',
            'delete',
            $name,
            '--repo',
            $this->repository,
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }
}
