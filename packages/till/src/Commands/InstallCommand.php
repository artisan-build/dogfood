<?php

namespace ArtisanBuild\Till\Commands;

use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    protected $signature = 'till:install
        {path?}
        {--undo : Clean up all published files as if the installation never happened }
        {--force : Run the installer even if it has already been run }';

    protected $description = 'Install and configure the till package';

    public function handle()
    {
        if ($this->option('undo')) {
            $this->undo();

            return self::SUCCESS;
        }

        if (file_exists(config_path('till.php')) && ! $this->option('force')) {
            if (! $this->confirm('It looks like you have already installed this package. Are you sure you want to continue?')) {
                $this->info('Installation aborted');

                return self::SUCCESS;
            }
        }

        if (! class_exists(User::class)) {
            $this->error('Please install a compatible authentication scaffolding package first.');

            return self::INVALID;
        }

        // Publish the configuration file
        $this->call('vendor:publish', ['--tag' => 'till']);

        $config = File::get(config_path('till.php'));

        // Ask where they want to keep the Plans directory (app/Plans by default)
        $plans = $this->argument('path') ?? text(
            label: 'Where do you want to put your Plans directory (relative to app/)',
            default: 'Plans',
        );

        $config = str_replace("base_path('packages/till/src/SubscriptionPlans')", "app_path('{$plans}')", $config);

        // Set the team mode as well as team (if applicable) and user models
        if (! class_exists(Team::class)) {
            $config = str_replace(["'team_mode' => true,", "use ArtisanBuild\Till\Models\TillTeam;\n"], ["'team_mode' => false,", ''], $config);

        } else {
            $config = str_replace('TillTeam::class', '\App\Models\Team::class', $config);
        }
        $config = str_replace(['TillUser::class', "use ArtisanBuild\Till\Models\TillUser;\n"], ['\App\Models\User::class', ''], $config);

        // Create the Plans directory
        File::ensureDirectoryExists(app_path($plans));
        File::ensureDirectoryExists(app_path($plans).'/Abilities');


        File::put(config_path('till.php'), $config);

        Config::set('till.plan_path', app_path($plans));

        $this->call('till:create-plan', ['name' => 'Default Plan', 'heading' => 'Default Plan', 'subheading' => 'This is the default plan for non-paying users', 'week' => 0, 'month' => 0, 'year' => 0, 'life' => 0]);

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
