<?php

namespace ArtisanBuild\GH\Commands;

class Repo extends BaseCommand
{
    protected string $config_key = 'gh.default_options.repo';

    public function __construct(
        public ?string $repository = null,
    ) {}

    public function repository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function archive(): string
    {
        $command = implode(' ', [
            'gh',
            'repo',
            'archive',
            $this->repository,
            $this->getOptions('archive'),
        ]);

        return $this->executeCommand($command);
    }

    public function clone(?string $directory = null, ?string $protocol = null)
    {
        $repository = match ($protocol) {
            'https' => 'https://github.com/'.$this->repository,
            'git' => 'git@github.com/'.$this->repository,
            default => $this->repository
        };

        $command = implode(' ', array_filter([
            'gh',
            'repo',
            'clone',
            $repository,
            $directory,
            $this->getOptions('clone'),
        ]));

        return $this->executeCommand($command);
    }

    public function create(): string
    {
        $command = implode(' ', [
            'gh',
            'repo',
            'create',
            $this->repository,
            $this->getOptions('create'),
        ]);

        return $this->executeCommand($command);
    }

    public function delete(): string
    {
        $command = implode(' ', [
            'gh',
            'repo',
            'delete',
            $this->repository,
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }

    public function deployKey(string $action, ?string $title = null, ?string $key = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'repo',
            'deploy-key',
            $action,
            $this->repository,
            $title,
            $key,
            $this->getOptions('deploy-key'),
        ]));

        return $this->executeCommand($command);
    }

    public function edit(): string
    {
        $command = implode(' ', [
            'gh',
            'repo',
            'edit',
            $this->repository,
            $this->getOptions('edit'),
        ]);

        return $this->executeCommand($command);
    }

    public function fork(?string $org = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'repo',
            'fork',
            $this->repository,
            $org ? '--org '.$org : null,
            $this->getOptions('fork'),
        ]));

        return $this->executeCommand($command);
    }

    public function list(string $owner)
    {
        $command = implode(' ', array_filter([
            'gh',
            'repo',
            'list',
            $owner,
            $this->getOptions('list'),
        ]));

        return $this->executeCommand($command);
    }

    public function rename(string $newName): string
    {
        $command = implode(' ', [
            'gh',
            'repo',
            'rename',
            $this->repository,
            $newName,
            $this->getOptions('rename'),
        ]);

        return $this->executeCommand($command);
    }

    public function setDefault(): string
    {
        $command = implode(' ', [
            'gh',
            'repo',
            'set-default',
            $this->repository,
            $this->getOptions('set-default'),
        ]);

        return $this->executeCommand($command);
    }

    public function sync(?string $branch = null, ?string $source = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'repo',
            'sync',
            $this->repository,
            $branch ? '--branch '.$branch : null,
            $source ? '--source '.$source : null,
            $this->getOptions('sync'),
        ]));

        return $this->executeCommand($command);
    }

    public function unarchive(): string
    {
        $command = implode(' ', [
            'gh',
            'repo',
            'unarchive',
            $this->repository,
            $this->getOptions('unarchive'),
        ]);

        return $this->executeCommand($command);
    }

    public function view(?string $branch = null, bool $web = false): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'repo',
            'view',
            $this->repository,
            $branch ? '--branch '.$branch : null,
            $web ? '--web' : null,
            $this->getOptions('view'),
        ]));

        return $this->executeCommand($command);
    }
}
