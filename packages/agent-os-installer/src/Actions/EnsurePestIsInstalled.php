<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * Ensure PestPHP is installed and configured as the default test runner
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsurePestIsInstalled
{
    /**
     * Execute the action to ensure Pest is installed
     */
    public function __invoke(Command $command): bool
    {
        if ($this->isPestInstalled()) {
            $command->info('✓ PestPHP is already installed');

            return true;
        }

        $command->info('Installing PestPHP...');

        if (! $this->installPest($command)) {
            $command->error('Failed to install PestPHP');

            return false;
        }

        $command->info('✓ PestPHP installed successfully');

        return true;
    }

    /**
     * Check if Pest is already installed
     */
    protected function isPestInstalled(): bool
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        return isset($composerJson['require-dev']['pestphp/pest'])
            || isset($composerJson['require-dev']['pestphp/pest-plugin-laravel']);
    }

    /**
     * Install Pest via Composer
     */
    protected function installPest(Command $command): bool
    {
        $process = new Process(
            ['composer', 'require', '--dev', 'pestphp/pest', 'pestphp/pest-plugin-laravel', '--with-all-dependencies'],
            base_path(),
            null,
            null,
            300
        );

        $process->run(function ($type, $buffer) use ($command): void {
            $command->getOutput()->write($buffer);
        });

        return $process->isSuccessful();
    }
}
