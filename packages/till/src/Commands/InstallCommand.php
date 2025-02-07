<?php

namespace ArtisanBuild\Till\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    protected $signature = 'till:install
        {--undo : Clean up all published files as if the installation never happened }';

    protected $description = 'Install and configure the till package';

    public function handle()
    {
        if ($this->option('undo')) {
            $this->undo();

            return self::SUCCESS;
        }

        // Publish the configuration file
        $this->call('vendor:publish', ['--tag' => 'till']);

        // Ask where they want to keep the Plans directory (app/Plans by default)
        $plans = text(
            label: 'Where do you want to put your Plans directory (relative to app/)',
            default: 'Plans',
        );

        // Ask whether they want to attach the subscriptions to team or user (Team by default)
        $team_mode = (bool) select(
            label: 'Do you want to attach subscriptions to teams or users?',
            options: [1 => 'Teams (Recommended)', 0 => 'Users']
        );

        // Update the published configuration file based on those values

        // Create the Plans directory
        File::ensureDirectoryExists(app_path($plans));

        return self::SUCCESS;

    }

    protected function undo()
    {
        $plans = text(
            label: 'Where did you put your Plans directory (relative to app/)',
            default: 'Plans',
        );
        File::delete(config_path('till.php'));
        File::deleteDirectory(app_path($plans));
    }
}
