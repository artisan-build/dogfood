<?php

namespace ArtisanBuild\GH\Commands;

class Run extends BaseCommand
{
    protected string $config_key = 'gh.default_options.run';

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
            'run',
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
            'run',
            'view',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('view'),
        ]);

        return $this->executeCommand($command);
    }

    public function rerun(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'run',
            'rerun',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('rerun'),
        ]);

        return $this->executeCommand($command);
    }

    public function cancel(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'run',
            'cancel',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('cancel'),
        ]);

        return $this->executeCommand($command);
    }

    public function download(string $id, ?string $dir = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'run',
            'download',
            $id,
            '--repo',
            $this->repository,
            $dir ? '--dir '.escapeshellarg($dir) : null,
            $this->getOptions('download'),
        ]));

        return $this->executeCommand($command);
    }
}
