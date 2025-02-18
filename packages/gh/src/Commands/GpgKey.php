<?php

namespace ArtisanBuild\GH\Commands;

class GpgKey extends BaseCommand
{
    protected string $config_key = 'gh.default_options.gpg-key';

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'gpg-key',
            'list',
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function add(string $keyFile): string
    {
        $command = implode(' ', [
            'gh',
            'gpg-key',
            'add',
            escapeshellarg($keyFile),
            $this->getOptions('add'),
        ]);

        return $this->executeCommand($command);
    }

    public function delete(string $id): string
    {
        $command = implode(' ', [
            'gh',
            'gpg-key',
            'delete',
            escapeshellarg($id),
            $this->getOptions('delete'),
        ]);

        return $this->executeCommand($command);
    }
}
