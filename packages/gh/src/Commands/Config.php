<?php

namespace ArtisanBuild\GH\Commands;

class Config extends BaseCommand
{
    protected string $config_key = 'gh.default_options.config';

    public function get(string $key): string
    {
        $command = implode(' ', [
            'gh',
            'config',
            'get',
            escapeshellarg($key),
            $this->getOptions('get'),
        ]);

        return $this->executeCommand($command);
    }

    public function set(string $key, string $value): string
    {
        $command = implode(' ', [
            'gh',
            'config',
            'set',
            escapeshellarg($key),
            escapeshellarg($value),
            $this->getOptions('set'),
        ]);

        return $this->executeCommand($command);
    }

    public function delete(string $key): string
    {
        $command = implode(' ', [
            'gh',
            'config',
            'delete',
            escapeshellarg($key),
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'config',
            'list',
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }
}
