<?php

namespace ArtisanBuild\GH\Commands;

class Release extends BaseCommand
{
    protected string $config_key = 'gh.default_options.release';

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
            'release',
            'list',
            '--repo',
            $this->repository,
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function view(string $tag): string
    {
        $command = implode(' ', [
            'gh',
            'release',
            'view',
            $tag,
            '--repo',
            $this->repository,
            $this->getOptions('view'),
        ]);

        return $this->executeCommand($command);
    }

    public function create(string $tag, ?string $title = null, ?string $notes = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'release',
            'create',
            $tag,
            $title ? '--title '.escapeshellarg($title) : null,
            $notes ? '--notes '.escapeshellarg($notes) : null,
            '--repo',
            $this->repository,
            $this->getOptions('create'),
        ]));

        return $this->executeCommand($command);
    }

    public function delete(string $tag): string
    {
        $command = implode(' ', [
            'gh',
            'release',
            'delete',
            $tag,
            '--repo',
            $this->repository,
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }

    public function uploadAsset(string $tag, string $filePath, ?string $label = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'release',
            'upload',
            $tag,
            escapeshellarg($filePath),
            $label ? '--clabel '.escapeshellarg($label) : null,
            '--repo',
            $this->repository,
            $this->getOptions('upload'),
        ]));

        return $this->executeCommand($command);
    }

    public function edit(string $tag, ?string $title = null, ?string $notes = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'release',
            'edit',
            $tag,
            $title ? '--title '.escapeshellarg($title) : null,
            $notes ? '--notes '.escapeshellarg($notes) : null,
            '--repo',
            $this->repository,
            $this->getOptions('edit'),
        ]));

        return $this->executeCommand($command);
    }
}
