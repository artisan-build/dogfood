<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

/**
 * Ensure Laravel Pint is installed and configured
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsurePintIsInstalled
{
    /**
     * Default Pint configuration rules
     */
    protected array $defaultRules = [
        'preset' => 'laravel',
        'rules' => [
            'declare_strict_types' => true,
            'fully_qualified_strict_types' => true,
            'single_trait_insert_per_statement' => true,
            'array_syntax' => true,
        ],
    ];

    /**
     * Execute the action to ensure Pint is installed
     */
    public function __invoke(Command $command): bool
    {
        if (! $this->isPintInstalled()) {
            $command->info('Installing Laravel Pint...');

            if (! $this->installPint($command)) {
                $command->error('Failed to install Laravel Pint');

                return false;
            }

            $command->info('✓ Laravel Pint installed successfully');
        } else {
            $command->info('✓ Laravel Pint is already installed');
        }

        // Ensure pint.json configuration
        $this->ensurePintConfiguration($command);

        return true;
    }

    /**
     * Check if Pint is already installed
     */
    protected function isPintInstalled(): bool
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        return isset($composerJson['require-dev']['laravel/pint']);
    }

    /**
     * Install Pint via Composer
     */
    protected function installPint(Command $command): bool
    {
        $result = Process::path(base_path())
            ->timeout(300)
            ->run('composer require --dev laravel/pint --with-all-dependencies', function ($type, $buffer) use ($command): void {
                $command->getOutput()->write($buffer);
            });

        return $result->successful();
    }

    /**
     * Ensure pint.json configuration exists and is properly configured
     */
    protected function ensurePintConfiguration(Command $command): void
    {
        $pintJsonPath = base_path('pint.json');

        if (! File::exists($pintJsonPath)) {
            // No pint.json exists, create one with our defaults
            File::put($pintJsonPath, json_encode($this->defaultRules, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
            $command->info('✓ Created pint.json with default configuration');

            return;
        }

        // pint.json exists, merge our rules with their existing rules
        $existingConfig = json_decode(File::get($pintJsonPath), true);
        $mergedConfig = $this->mergeConfiguration($existingConfig, $this->defaultRules);

        // Only update if changes were made
        if ($existingConfig !== $mergedConfig) {
            File::put($pintJsonPath, json_encode($mergedConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");
            $command->info('✓ Updated pint.json with recommended rules');
        } else {
            $command->info('✓ pint.json configuration is up to date');
        }
    }

    /**
     * Merge configurations, preferring existing values over defaults
     */
    protected function mergeConfiguration(array $existing, array $defaults): array
    {
        $merged = $existing;

        // Set preset if not already set
        if (! isset($merged['preset']) && isset($defaults['preset'])) {
            $merged['preset'] = $defaults['preset'];
        }

        // Ensure rules array exists
        if (! isset($merged['rules'])) {
            $merged['rules'] = [];
        }

        // Merge rules, but only add missing ones (don't override existing)
        foreach ($defaults['rules'] as $rule => $value) {
            if (! array_key_exists($rule, $merged['rules'])) {
                $merged['rules'][$rule] = $value;
            }
        }

        return $merged;
    }
}
