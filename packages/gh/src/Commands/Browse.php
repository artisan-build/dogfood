<?php

namespace ArtisanBuild\GH\Commands;

class Browse extends BaseCommand
{
    protected string $config_key = 'gh.default_options.browse';

    public function __construct(
        public ?string $repository = null,
    ) {}

    public function repository(string $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function open(?string $branch = null, ?string $commit = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'browse',
            $this->repository,
            $branch ? '--branch '.escapeshellarg($branch) : null,
            $commit ? '--commit '.escapeshellarg($commit) : null,
            $this->getOptions('open'),
        ]));

        return $this->executeCommand($command);
    }
}
