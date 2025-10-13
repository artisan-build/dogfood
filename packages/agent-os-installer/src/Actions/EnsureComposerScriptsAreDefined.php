<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Ensure required Composer scripts are defined
 *
 * These scripts are required for Agent OS commands and instructions to function properly.
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsureComposerScriptsAreDefined
{
    /**
     * Required Composer scripts
     */
    protected array $requiredScripts = [
        'test' => [
            '@php artisan config:clear --ansi',
            '@php artisan test',
        ],
        'test-parallel' => [
            '@php artisan config:clear --ansi',
            '@php artisan test --parallel --recreate-databases',
        ],
        'lint' => [
            'vendor/bin/duster fix',
        ],
        'rector' => [
            'vendor/bin/rector',
        ],
        'stan' => [
            'vendor/bin/phpstan analyse --memory-limit=512M',
        ],
        'ready' => [
            '@php artisan config:clear --ansi',
            '@php artisan ide-helper:models --write',
            'composer rector',
            'composer lint',
            'composer stan',
            'composer test',
        ],
        'report' => [
            '@php artisan config:clear --ansi || true',
            '@php artisan ide-helper:models --write || true',
            'composer rector || true',
            'composer lint || true',
            'composer stan || true',
            'composer test || true',
        ],
        'coverage-html' => [
            'XDEBUG_MODE=coverage herd debug ./vendor/bin/pest --coverage-php coverage.php',
            '@php artisan generate-code-coverage-html',
        ],
        'coverage' => [
            'XDEBUG_MODE=coverage herd debug ./vendor/bin/pest --coverage',
        ],
        'types' => [
            'vendor/bin/pest --type-coverage',
        ],
    ];

    /**
     * Execute the action to ensure Composer scripts are defined
     */
    public function __invoke(Command $command): bool
    {
        $composerJsonPath = base_path('composer.json');
        $composerJson = json_decode(File::get($composerJsonPath), true);

        $scriptsToUpdate = $this->getScriptsNeedingUpdate($composerJson['scripts'] ?? []);

        if (empty($scriptsToUpdate)) {
            $command->info('✓ All required Composer scripts are properly defined');

            return true;
        }

        // Check if any existing scripts would be overwritten
        $conflictingScripts = $this->getConflictingScripts($scriptsToUpdate, $composerJson['scripts'] ?? []);

        if (! empty($conflictingScripts)) {
            $command->newLine();
            $command->warn('The following Composer scripts are defined but differ from Agent OS requirements:');
            foreach ($conflictingScripts as $scriptName) {
                $command->line("  - {$scriptName}");
            }
            $command->newLine();

            if (! $command->confirm('Would you like to overwrite these scripts with Agent OS optimized versions?', true)) {
                $command->newLine();
                $command->error('Installation cannot continue without required Composer scripts.');
                $command->line('Agent OS relies on these scripts being defined exactly as specified for proper operation.');
                $command->line('The required scripts are used in agent-os commands and instructions.');

                return false;
            }
        }

        // Update the scripts
        if (! isset($composerJson['scripts'])) {
            $composerJson['scripts'] = [];
        }

        foreach ($scriptsToUpdate as $scriptName => $scriptCommands) {
            $composerJson['scripts'][$scriptName] = $scriptCommands;
        }

        // Write back to composer.json
        File::put(
            $composerJsonPath,
            json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n"
        );

        $command->info('✓ Composer scripts updated successfully');

        return true;
    }

    /**
     * Get scripts that need to be added or updated
     */
    protected function getScriptsNeedingUpdate(array $existingScripts): array
    {
        $scriptsToUpdate = [];

        foreach ($this->requiredScripts as $scriptName => $scriptCommands) {
            if (! isset($existingScripts[$scriptName]) || $existingScripts[$scriptName] !== $scriptCommands) {
                $scriptsToUpdate[$scriptName] = $scriptCommands;
            }
        }

        return $scriptsToUpdate;
    }

    /**
     * Get scripts that exist but have different definitions
     */
    protected function getConflictingScripts(array $scriptsToUpdate, array $existingScripts): array
    {
        $conflicts = [];

        foreach ($scriptsToUpdate as $scriptName => $scriptCommands) {
            if (isset($existingScripts[$scriptName]) && $existingScripts[$scriptName] !== $scriptCommands) {
                $conflicts[] = $scriptName;
            }
        }

        return $conflicts;
    }
}
