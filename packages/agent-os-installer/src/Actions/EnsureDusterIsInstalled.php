<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * Ensure Tighten Duster is installed
 *
 * Duster combines Pint, TLINT, and other code quality tools into a single command.
 *
 * Minimum Requirements:
 * - PHP 8.2+
 * - Laravel 11.0+
 * - Composer 2.0+
 */
class EnsureDusterIsInstalled
{
    /**
     * Execute the action to ensure Duster is installed
     */
    public function __invoke(Command $command): bool
    {
        if ($this->isDusterInstalled()) {
            $command->info('✓ Tighten Duster is already installed');

            return true;
        }

        $command->info('Installing Tighten Duster...');

        if (! $this->installDuster($command)) {
            $command->error('Failed to install Tighten Duster');

            return false;
        }

        $command->info('✓ Tighten Duster installed successfully');

        return true;
    }

    /**
     * Check if Duster is already installed
     */
    protected function isDusterInstalled(): bool
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);

        return isset($composerJson['require-dev']['tightenco/duster']);
    }

    /**
     * Install Duster via Composer
     */
    protected function installDuster(Command $command): bool
    {
        $process = new Process(
            ['composer', 'require', '--dev', 'tightenco/duster', '--with-all-dependencies'],
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
