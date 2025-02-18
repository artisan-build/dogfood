<?php

namespace ArtisanBuild\GH\Commands;

class Status extends BaseCommand
{
    protected string $config_key = 'gh.default_options.status';

    public function __construct(
        public ?string $repository = null,
    ) {}

    public function repository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function show(): string
    {
        $command = implode(' ', [
            'gh',
            'status',
            $this->getOptions('show'),
        ]);

        return $this->executeCommand($command);
    }
}
