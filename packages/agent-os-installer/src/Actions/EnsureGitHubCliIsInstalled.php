<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

/**
 * Ensure the GitHub CLI (gh) is installed on the system
 */
class EnsureGitHubCliIsInstalled
{
    /**
     * Execute the action to ensure GitHub CLI is installed
     */
    public function __invoke(Command $command): bool
    {
        if ($this->isGitHubCliInstalled()) {
            $command->info('✓ GitHub CLI (gh) is installed');

            return true;
        }

        $command->error('✗ GitHub CLI (gh) is not installed');
        $command->newLine();
        $command->line('The GitHub CLI is required for Agent OS to function properly.');
        $command->line('Please install it from: https://cli.github.com/');
        $command->newLine();
        $command->line('Installation instructions:');
        $command->line('  macOS:   brew install gh');
        $command->line('  Windows: winget install --id GitHub.cli');
        $command->line('  Linux:   See https://github.com/cli/cli/blob/trunk/docs/install_linux.md');

        return false;
    }

    /**
     * Check if GitHub CLI is installed by checking if the gh command exists
     */
    protected function isGitHubCliInstalled(): bool
    {
        $result = Process::run('which gh');

        return $result->successful();
    }
}
