<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Yaml\Yaml;

/**
 * Ensure PHPStan/Larastan is installed and configured
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsurePhpStanIsInstalled
{
    protected const RECOMMENDED_LEVEL = 5;

    /**
     * Default PHPStan configuration
     */
    protected array $defaultConfig = [
        'includes' => [
            './vendor/larastan/larastan/extension.neon',
        ],
        'parameters' => [
            'paths' => [
                'app/',
            ],
            'excludePaths' => [
                '**/*Test.php',
            ],
            'level' => 6,
            'treatPhpDocTypesAsCertain' => false,
        ],
    ];

    /**
     * Execute the action to ensure PHPStan is installed
     */
    public function __invoke(Command $command): bool
    {
        if (! $this->isPhpStanInstalled()) {
            $command->info('Installing PHPStan/Larastan...');

            if (! $this->installPhpStan($command)) {
                $command->error('Failed to install PHPStan/Larastan');

                return false;
            }

            $command->info('✓ PHPStan/Larastan installed successfully');
        } else {
            $command->info('✓ PHPStan/Larastan is already installed');
        }

        // Ensure phpstan.neon configuration
        $this->ensurePhpStanConfiguration($command);

        return true;
    }

    /**
     * Check if PHPStan is already installed
     */
    protected function isPhpStanInstalled(): bool
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        return isset($composerJson['require-dev']['larastan/larastan'])
            || isset($composerJson['require-dev']['phpstan/phpstan']);
    }

    /**
     * Install PHPStan via Composer
     */
    protected function installPhpStan(Command $command): bool
    {
        $process = new Process(
            ['composer', 'require', '--dev', 'larastan/larastan', '--with-all-dependencies'],
            base_path(),
            null,
            null,
            300
        );

        $process->run(function ($type, $buffer) use ($command) {
            $command->getOutput()->write($buffer);
        });

        return $process->isSuccessful();
    }

    /**
     * Ensure phpstan.neon configuration exists and is properly configured
     */
    protected function ensurePhpStanConfiguration(Command $command): void
    {
        $phpstanNeonPath = base_path('phpstan.neon');

        if (! File::exists($phpstanNeonPath)) {
            // No phpstan.neon exists, create one with our defaults
            File::put($phpstanNeonPath, Yaml::dump($this->defaultConfig, 4, 2));
            $command->info('✓ Created phpstan.neon with default configuration (level 6)');

            return;
        }

        // phpstan.neon exists, check the level
        // Parse manually to avoid issues with glob patterns
        $existingContent = File::get($phpstanNeonPath);
        $currentLevel = $this->extractPhpStanLevel($existingContent);

        if ($currentLevel !== null && $currentLevel < self::RECOMMENDED_LEVEL) {
            $command->warn("⚠ PHPStan level is currently set to {$currentLevel}. We recommend level ".self::RECOMMENDED_LEVEL.' or higher when using LLM code generation to ensure proper typing.');
        } else {
            $command->info('✓ phpstan.neon configuration is acceptable (preserving existing configuration)');
        }
    }

    /**
     * Extract PHPStan level from configuration content
     */
    protected function extractPhpStanLevel(string $content): ?int
    {
        if (preg_match('/^\s*level:\s*(\d+)/m', $content, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }
}
