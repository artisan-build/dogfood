<?php

namespace ArtisanBuild\GH\Commands;

class Issue extends BaseCommand
{
    protected string $config_key = 'gh.default_options.issue';

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
            'issue',
            'list',
            '--repo',
            $this->repository,
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function view(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'issue',
            'view',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('view'),
        ]);

        return $this->executeCommand($command);
    }

    public function create(?string $title = null, ?string $body = null, array $options = []): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'issue',
            'create',
            '--repo',
            $this->repository,
            $title ? '--title '.escapeshellarg($title) : null,
            $body ? '--body '.escapeshellarg($body) : null,
            $this->getOptions('create'),
        ]));

        return $this->executeCommand($command);
    }

    public function edit(string $id, ?string $title = null, ?string $body = null, array $options = []): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'issue',
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
            'issue',
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
            'issue',
            'reopen',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('reopen'),
        ]);

        return $this->executeCommand($command);
    }

    public function comment(string $id, string $body): string
    {
        $command = implode(' ', [
            'gh',
            'issue',
            'comment',
            $id,
            '--repo',
            $this->repository,
            '--body',
            escapeshellarg($body),
            $this->getOptions('comment'),
        ]);

        return $this->executeCommand($command);
    }
}
