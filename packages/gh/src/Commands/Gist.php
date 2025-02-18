<?php

namespace ArtisanBuild\GH\Commands;

class Gist extends BaseCommand
{
    protected string $config_key = 'gh.default_options.gist';

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'gist',
            'list',
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function view(string $id, bool $raw = false): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'gist',
            'view',
            $id,
            $raw ? '--raw' : null,
            $this->getOptions('view'),
        ]));

        return $this->executeCommand($command);
    }

    public function create(array $files, ?string $description = null, bool $public = false): string
    {
        $fileArgs = implode(' ', array_map('escapeshellarg', $files));

        $command = implode(' ', array_filter([
            'gh',
            'gist',
            'create',
            $fileArgs,
            $description ? '--desc '.escapeshellarg($description) : null,
            $public ? '--public' : null,
            $this->getOptions('create'),
        ]));

        return $this->executeCommand($command);
    }

    public function edit(string $id, array $files, ?string $description = null): string
    {
        $fileArgs = implode(' ', array_map('escapeshellarg', $files));

        $command = implode(' ', array_filter([
            'gh',
            'gist',
            'edit',
            $id,
            $fileArgs,
            $description ? '--desc '.escapeshellarg($description) : null,
            $this->getOptions('edit'),
        ]));

        return $this->executeCommand($command);
    }

    public function delete(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'gist',
            'delete',
            $id,
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }

    public function fork(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'gist',
            'fork',
            $id,
            $this->getOptions('fork'),
        ]);

        return $this->executeCommand($command);
    }
}
