<?php

namespace ArtisanBuild\GH\Commands;

class Org extends BaseCommand
{
    protected string $config_key = 'gh.default_options.org';

    public function __construct(
        public ?string $organization = null,
    ) {}

    public function organization(string $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function list(): string
    {
        $command = implode(' ', [
            'gh',
            'org',
            'list',
            $this->getOptions('list'),
        ]);

        return $this->executeCommand($command);
    }

    public function view(): string
    {
        $command = implode(' ', [
            'gh',
            'org',
            'view',
            $this->organization,
            $this->getOptions('view'),
        ]);

        return $this->executeCommand($command);
    }

    public function members(): string
    {
        $command = implode(' ', [
            'gh',
            'org',
            'members',
            $this->organization,
            $this->getOptions('members'),
        ]);

        return $this->executeCommand($command);
    }

    public function invitations(): string
    {
        $command = implode(' ', [
            'gh',
            'org',
            'invitations',
            $this->organization,
            $this->getOptions('invitations'),
        ]);

        return $this->executeCommand($command);
    }

    public function auditLog(): string
    {
        $command = implode(' ', [
            'gh',
            'org',
            'audit-log',
            $this->organization,
            $this->getOptions('audit-log'),
        ]);

        return $this->executeCommand($command);
    }
}
