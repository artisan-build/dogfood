<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Ensure the Claude GitHub App has been installed via Claude Code
 */
class EnsureClaudeGitHubAppIsInstalled
{
    /**
     * Execute the action to ensure Claude GitHub App is installed
     */
    public function __invoke(Command $command): bool
    {
        $claudeYmlPath = base_path('.github/claude.yml');
        $claudeCodeReviewPath = base_path('.github/claude-code-review.yml');

        if (File::exists($claudeYmlPath) && File::exists($claudeCodeReviewPath)) {
            $command->info('✓ Claude GitHub App is installed');

            return true;
        }

        $command->error('✗ Claude GitHub App is not installed');
        $command->newLine();
        $command->line('The Claude GitHub App must be installed before optimizing reviews.');
        $command->newLine();
        $command->line('<fg=yellow>To install the Claude GitHub App:</>');
        $command->line('  1. Open Claude Code (claude.ai/code)');
        $command->line('  2. Run the command: <fg=cyan>/install-github-app</>');
        $command->line('  3. Follow the prompts to authenticate and install the app');
        $command->line('  4. Once complete, run this command again');
        $command->newLine();
        $command->line('This will create the following files:');
        $command->line('  - .github/claude.yml');
        $command->line('  - .github/claude-code-review.yml');

        return false;
    }
}
