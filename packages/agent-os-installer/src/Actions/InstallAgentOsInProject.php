<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Install Agent OS in the current project using the project-install.sh script
 */
class InstallAgentOsInProject
{
    /**
     * Execute the action to install Agent OS in the project
     */
    public function __invoke(Command $command): bool
    {
        $command->info('Installing Agent OS in project...');

        $homeDir = \Illuminate\Support\Facades\Request::server('HOME') ?? \Illuminate\Support\Facades\Request::server('USERPROFILE') ?? '~';
        $installScript = $homeDir.'/agent-os/scripts/project-install.sh';

        $process = new Process(
            [
                $installScript,
                '--multi-agent-mode',
                'true',
                '--single-agent-mode',
                'true',
                '--profile',
                'laravel',
            ],
            base_path(),
            null,
            null,
            300
        );

        $process->run(function ($type, $buffer) use ($command): void {
            $command->getOutput()->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $command->error('Failed to install Agent OS in project');

            return false;
        }

        $command->info('✓ Agent OS installed in project successfully');

        return true;
    }
}
