<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

/**
 * Ensure Agent OS is installed in the user's home directory with Laravel profile
 */
class EnsureAgentOsIsInstalled
{
    /**
     * Execute the action to ensure Agent OS is installed
     */
    public function __invoke(Command $command): bool
    {
        $homeDir = $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? '~';
        $agentOsPath = $homeDir.'/agent-os';
        $laravelProfilePath = $agentOsPath.'/profiles/laravel';

        // State 3: Laravel profile exists - all good
        if (File::isDirectory($laravelProfilePath)) {
            $command->info('✓ Agent OS with Laravel profile is installed');

            return true;
        }

        // State 1: Agent OS not installed at all
        if (! File::isDirectory($agentOsPath)) {
            return $this->installAgentOs($command, $homeDir);
        }

        // State 2: Agent OS installed but missing Laravel profile
        return $this->installLaravelProfile($command, $agentOsPath);
    }

    /**
     * Clone the Agent OS repository to the user's home directory
     */
    protected function installAgentOs(Command $command, string $homeDir): bool
    {
        $command->warn('Agent OS is not installed in your home directory.');
        $command->line('This will clone the Agent OS repository to: '.$homeDir.'/agent-os');
        $command->newLine();

        if (! $command->confirm('Would you like to install Agent OS now?', true)) {
            $command->error('Agent OS installation is required to continue.');

            return false;
        }

        $command->info('Cloning Agent OS repository...');

        $process = new Process(
            ['gh', 'repo', 'clone', 'artisan-build/agent-os', 'agent-os'],
            $homeDir,
            null,
            null,
            300
        );

        $process->run(function ($type, $buffer) use ($command) {
            $command->getOutput()->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $command->error('Failed to clone Agent OS repository');

            return false;
        }

        $command->info('✓ Agent OS installed successfully');

        return true;
    }

    /**
     * Install the Laravel profile by cloning just the profiles/laravel directory
     */
    protected function installLaravelProfile(Command $command, string $agentOsPath): bool
    {
        $command->warn('Agent OS is installed but missing the Laravel profile.');
        $command->line('This will add the Laravel profile to: '.$agentOsPath.'/profiles/laravel');
        $command->newLine();

        if (! $command->confirm('Would you like to install the Laravel profile now?', true)) {
            $command->error('Laravel profile installation is required to continue.');

            return false;
        }

        $command->info('Installing Laravel profile...');

        // Create profiles directory if it doesn't exist
        $profilesDir = $agentOsPath.'/profiles';
        if (! File::isDirectory($profilesDir)) {
            File::makeDirectory($profilesDir, 0755, true);
        }

        // Clone the repo to a temp directory
        $tempDir = sys_get_temp_dir().'/agent-os-temp-'.uniqid();

        $cloneProcess = new Process(
            ['gh', 'repo', 'clone', 'artisan-build/agent-os', $tempDir, '--', '--depth', '1'],
            null,
            null,
            null,
            300
        );

        $cloneProcess->run();

        if (! $cloneProcess->isSuccessful()) {
            $command->error('Failed to clone Agent OS repository');

            return false;
        }

        // Copy just the profiles/laravel directory
        $sourcePath = $tempDir.'/profiles/laravel';
        $destPath = $profilesDir.'/laravel';

        if (! File::isDirectory($sourcePath)) {
            $command->error('Laravel profile not found in repository');
            File::deleteDirectory($tempDir);

            return false;
        }

        File::copyDirectory($sourcePath, $destPath);

        // Clean up temp directory
        File::deleteDirectory($tempDir);

        $command->info('✓ Laravel profile installed successfully');

        return true;
    }
}
