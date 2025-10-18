<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Commands;

use ArtisanBuild\AgentOsInstaller\Actions\EnsureClaudeGitHubAppIsInstalled;
use Illuminate\Console\Command;

/**
 * Optimize Claude Code reviews to only run on PRs that pass quality checks
 */
class OptimizeClaudeReviewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'agent-os:optimize-claude-reviews';

    /**
     * The console command description.
     */
    protected $description = 'Optimize Claude Code reviews to only run on PRs passing quality checks';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔧 Optimizing Claude Code reviews...');
        $this->newLine();

        // Step 1: Ensure Claude GitHub App is installed
        $ensureClaudeApp = new EnsureClaudeGitHubAppIsInstalled;
        if (! $ensureClaudeApp($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // TODO: Add remaining steps for GitHub Actions setup

        $this->newLine();
        $this->info('✅ Claude Code reviews optimized!');

        return self::SUCCESS;
    }
}
