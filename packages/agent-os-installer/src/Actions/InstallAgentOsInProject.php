<?php

declare(strict_types=1);

namespace ArtisanBuild\AgentOsInstaller\Actions;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Request;

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

        $homeDir = Request::server('HOME') ?? Request::server('USERPROFILE') ?? '~';
        $installScript = $homeDir.'/agent-os/scripts/project-install.sh';

        $result = Process::path(base_path())
            ->timeout(300)
            ->run($installScript.' --multi-agent-mode true --single-agent-mode true --profile laravel', function ($type, $buffer) use ($command): void {
                $command->getOutput()->write($buffer);
            });

        if (! $result->successful()) {
            $command->error('Failed to install Agent OS in project');

            return false;
        }

        $command->info('✓ Agent OS installed in project successfully');

        return true;
    }
}
