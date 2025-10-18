<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Commands;

use ArtisanBuild\AgentOsInstaller\Actions\EnsureAgentOsIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsureComposerScriptsAreDefined;
use ArtisanBuild\AgentOsInstaller\Actions\EnsureDevelopmentToolsAreInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsureDusterIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsureEnlightnIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsureGitHubCliIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsurePestIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsurePhpCodeSnifferIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsurePhpStanIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsurePintIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\EnsureRectorIsInstalled;
use ArtisanBuild\AgentOsInstaller\Actions\InstallAgentOsInProject;
use Illuminate\Console\Command;

/**
 * Install Agent OS and related code quality tools
 */
class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'agent-os:install';

    /**
     * The console command description.
     */
    protected $description = 'Install Agent OS and related code quality tools';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Installing Agent OS and code quality tools...');
        $this->newLine();

        // Step 1: Ensure GitHub CLI is installed
        $ensureGitHubCli = new EnsureGitHubCliIsInstalled;
        if (! $ensureGitHubCli($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 2: Ensure Agent OS is installed
        $ensureAgentOs = new EnsureAgentOsIsInstalled;
        if (! $ensureAgentOs($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 3: Ensure Pest is installed
        $ensurePest = new EnsurePestIsInstalled;
        if (! $ensurePest($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 4: Ensure Pint is installed
        $ensurePint = new EnsurePintIsInstalled;
        if (! $ensurePint($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 5: Ensure PHPStan is installed
        $ensurePhpStan = new EnsurePhpStanIsInstalled;
        if (! $ensurePhpStan($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 6: Ensure Rector is installed
        $ensureRector = new EnsureRectorIsInstalled;
        if (! $ensureRector($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 7: Ensure Duster is installed
        $ensureDuster = new EnsureDusterIsInstalled;
        if (! $ensureDuster($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 8: Ensure PHP_CodeSniffer is installed
        $ensurePhpCodeSniffer = new EnsurePhpCodeSnifferIsInstalled;
        if (! $ensurePhpCodeSniffer($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 9: Ensure Enlightn is installed
        $ensureEnlightn = new EnsureEnlightnIsInstalled;
        if (! $ensureEnlightn($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 10: Ensure development tools are installed
        $ensureDevelopmentTools = new EnsureDevelopmentToolsAreInstalled;
        if (! $ensureDevelopmentTools($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 11: Ensure Composer scripts are defined
        $ensureComposerScripts = new EnsureComposerScriptsAreDefined;
        if (! $ensureComposerScripts($this)) {
            return self::FAILURE;
        }

        $this->newLine();

        // Step 12: Install Agent OS in project
        $installAgentOsInProject = new InstallAgentOsInProject;
        if (! $installAgentOsInProject($this)) {
            return self::FAILURE;
        }

        $this->newLine();
        $this->info('✅ Installation complete!');

        return self::SUCCESS;
    }
}
