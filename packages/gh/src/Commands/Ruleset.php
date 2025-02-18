<?php

namespace ArtisanBuild\GH\Commands;

class Ruleset extends BaseCommand
{
    protected string $config_key = 'gh.default_options.ruleset';

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
            'ruleset',
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
            'ruleset',
            'view',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('view'),
        ]);

        return $this->executeCommand($command);
    }

    public function create(array $params): string
    {
        $paramArgs = array_map(
            fn ($key, $value) => "--$key ".escapeshellarg((string) $value),
            array_keys($params),
            $params
        );

        $command = implode(' ', array_merge([
            'gh',
            'ruleset',
            'create',
            '--repo',
            $this->repository,
        ], $paramArgs, [$this->getOptions('create')]));

        return $this->executeCommand($command);
    }

    public function edit(string $id, array $params): string
    {
        $paramArgs = array_map(
            fn ($key, $value) => "--$key ".escapeshellarg((string) $value),
            array_keys($params),
            $params
        );

        $command = implode(' ', array_merge([
            'gh',
            'ruleset',
            'edit',
            $id,
            '--repo',
            $this->repository,
        ], $paramArgs, [$this->getOptions('edit')]));

        return $this->executeCommand($command);
    }

    public function delete(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'ruleset',
            'delete',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }

    public function enable(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'ruleset',
            'enable',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('enable'),
        ]);

        return $this->executeCommand($command);
    }

    public function disable(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'ruleset',
            'disable',
            $id,
            '--repo',
            $this->repository,
            $this->getOptions('disable'),
        ]);

        return $this->executeCommand($command);
    }
}
