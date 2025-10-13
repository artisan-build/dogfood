<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * Ensure development helper tools are installed
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsureDevelopmentToolsAreInstalled
{
    /**
     * Required development packages
     */
    protected array $requiredPackages = [
        'barryvdh/laravel-debugbar',
        'barryvdh/laravel-ide-helper',
    ];

    /**
     * Execute the action to ensure development tools are installed
     */
    public function __invoke(Command $command): bool
    {
        $missingPackages = $this->getMissingPackages();

        if (empty($missingPackages)) {
            $command->info('✓ Development tools are already installed');

            return true;
        }

        $command->info('Installing development tools: '.implode(', ', $missingPackages));

        if (! $this->installPackages($missingPackages, $command)) {
            $command->error('Failed to install development tools');

            return false;
        }

        $command->info('✓ Development tools installed successfully');

        return true;
    }

    /**
     * Get list of missing packages
     */
    protected function getMissingPackages(): array
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);
        $installedPackages = $composerJson['require-dev'] ?? [];

        $missing = [];
        foreach ($this->requiredPackages as $package) {
            if (! isset($installedPackages[$package])) {
                $missing[] = $package;
            }
        }

        return $missing;
    }

    /**
     * Install missing packages via Composer
     */
    protected function installPackages(array $packages, Command $command): bool
    {
        $process = new Process(
            array_merge(
                ['composer', 'require', '--dev'],
                $packages,
                ['--with-all-dependencies']
            ),
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
}
