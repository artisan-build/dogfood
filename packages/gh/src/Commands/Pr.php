<?php

namespace ArtisanBuild\GH\Commands;

class Pr extends BaseCommand
{
    protected string $config_key = 'gh.default_options.pr';

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
            'pr',
            'list',
            '--repo',
            $this->repository,
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function view(string $id, bool $web = false): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'pr',
            'view',
            $id,
            '--repo',
            $this->repository,
            $web ? '--web' : null,
            $this->getOptions('view'),
        ]));

        return $this->executeCommand($command);
    }

    public function create(?string $title = null, ?string $body = null, array $options = []): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'pr',
            'create',
            '--repo',
            $this->repository,
            $title ? '--title '.escapeshellarg($title) : null,
            $body ? '--body '.escapeshellarg($body) : null,
            $this->getOptions('create'),
            implode(' ', array_map(fn ($opt) => escapeshellarg((string) $opt), $options)),
        ]));

        return $this->executeCommand($command);
    }

    public function edit(string $id, ?string $title = null, ?string $body = null, array $options = []): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'pr',
            'edit',
            $id,
            '--repo',
            $this->repository,
            $title ? '--title '.escapeshellarg($title) : null,
            $body ? '--body '.escapeshellarg($body) : null,
            $this->getOptions('edit'),
            implode(' ', array_map(fn ($opt) => escapeshellarg((string) $opt), $options)),
        ]));

        return $this->executeCommand($command);
    }

    public function close(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'pr',
            'close',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('close'),
        ]);

        return $this->executeCommand($command);
    }

    public function reopen(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'pr',
            'reopen',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('reopen'),
        ]);

        return $this->executeCommand($command);
    }

    public function merge(string $id, bool $squash = false, bool $rebase = false): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'pr',
            'merge',
            $id,
            '--repo',
            $this->repository,
            $squash ? '--squash' : null,
            $rebase ? '--rebase' : null,
            $this->getOptions('merge'),
        ]));

        return $this->executeCommand($command);
    }
}
