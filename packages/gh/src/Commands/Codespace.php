<?php

namespace ArtisanBuild\GH\Commands;

class Codespace extends BaseCommand
{
    protected string $config_key = 'gh.default_options.codespace';

    public function __construct(public ?string $repository = null) {}

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'codespace',
            'list',
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function create(?string $repo = null, ?string $branch = null, ?string $machine = null): string
    {
        $command = implode(' ', array_filter([
            'gh',
            'codespace',
            'create',
            $repo ? '--repo '.escapeshellarg($repo) : null,
            $branch ? '--branch '.escapeshellarg($branch) : null,
            $machine ? '--machine '.escapeshellarg($machine) : null,
            $this->getOptions('create'),
        ]));

        return $this->executeCommand($command);
    }

    public function delete(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'codespace',
            'delete',
            '--codespace',
            escapeshellarg($name),
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }

    public function stop(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'codespace',
            'stop',
            '--codespace',
            escapeshellarg($name),
            $this->getOptions('stop'),
        ]);

        return $this->executeCommand($command);
    }

    public function logs(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'codespace',
            'logs',
            '--codespace',
            escapeshellarg($name),
            $this->getOptions('logs'),
        ]);

        return $this->executeCommand($command);
    }

    public function ports(string $name): string
    {
        $command = implode(' ', [
            'gh',
            'codespace',
            'ports',
            '--codespace',
            escapeshellarg($name),
            $this->getOptions('ports'),
        ]);

        return $this->executeCommand($command);
    }
}
