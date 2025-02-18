<?php

namespace ArtisanBuild\GH\Commands;

class Project extends BaseCommand
{
    protected string $config_key = 'gh.default_options.project';

    public function __construct(
        public ?string $owner = null,
    ) {}

    public function owner(string $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'project',
            'list',
            '--owner',
            $this->owner,
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function view(int $id): string
    {
        $command = implode(' ', [
            'gh',
            'project',
            'view',
            $id,
            '--owner',
            $this->owner,
            $this->getOptions('view'),
        ]);

        return $this->executeCommand($command);
    }

    public function create(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'project',
            'create',
            '--name',
            escapeshellarg($name),
            '--owner',
            $this->owner,
            $this->getOptions('create'),
        ]);

        return $this->executeCommand($command);
    }

    public function delete(int $id): string
    {
        $command = implode(' ', [
            'gh',
            'project',
            'delete',
            $id,
            '--owner',
            $this->owner,
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }

    public function edit(int $id, array $fields): string
    {
        $fieldArgs = array_map(
            fn ($key, $value) => "--$key ".escapeshellarg((string) $value),
            array_keys($fields),
            $fields
        );

        $command = implode(' ', array_merge([
            'gh',
            'project',
            'edit',
            $id,
            '--owner',
            $this->owner,
        ], $fieldArgs, [$this->getOptions('edit')]));

        return $this->executeCommand($command);
    }

    public function copy(int $id, ?string $name = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'project',
            'copy',
            $id,
            '--owner',
            $this->owner,
            $name ? '--name '.escapeshellarg($name) : null,
            $this->getOptions('copy'),
        ]));

        return $this->executeCommand($command);
    }
}
