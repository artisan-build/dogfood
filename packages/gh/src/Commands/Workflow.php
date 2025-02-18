<?php

namespace ArtisanBuild\GH\Commands;

class Workflow extends BaseCommand
{
    protected string $config_key = 'gh.default_options.workflow';

    public function __construct(
        public ?string $repository = null,
    ) {}

    public function repository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function enable(string $workflow): string
    {
        $command = implode(' ', [
            'gh',
            'workflow',
            'enable',
            $workflow,
            '--repo',
            $this->repository,
            $this->getOptions('enable'),
        ]);

        return $this->executeCommand($command);
    }

    public function disable(string $workflow): string
    {
        $command = implode(' ', [
            'gh',
            'workflow',
            'disable',
            $workflow,
            '--repo',
            $this->repository,
            $this->getOptions('disable'),
        ]);

        return $this->executeCommand($command);
    }

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'workflow',
            'list',
            '--repo',
            $this->repository,
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function run(string $workflow, ?string $ref = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'workflow',
            'run',
            $workflow,
            '--repo',
            $this->repository,
            $ref ? '--ref '.$ref : null,
            $this->getOptions('run'),
        ]));

        return $this->executeCommand($command);
    }

    public function view(string $workflow): string
    {
        $command = implode(' ', [
            'gh',
            'workflow',
            'view',
            $workflow,
            '--repo',
            $this->repository,
            $this->getOptions('view'),
        ]);

        return $this->executeCommand($command);
    }
}
