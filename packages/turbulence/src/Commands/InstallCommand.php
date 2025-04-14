<?php

namespace ArtisanBuild\Turbulence\Commands;

use App\Models\User;
use ArtisanBuild\Turbulence\Rectors\AddHasAccountsTraitRector;
use ArtisanBuild\Turbulence\Rectors\AddRoleCastRector;
use ArtisanBuild\Turbulence\Support\RunRector;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'turbulence:install {--force}';

    public function handle(): int
    {
        if (config('turbulence.installed') && ! $this->option('force')) {
            $this->error('This installer has already been run. Add --force if you really want to do it again.');

            return self::INVALID;
        }

        if (! str_contains(File::get(base_path('composer.json')), 'rector/rector')) {
            Process::run('composer require rector/rector --dev');
        }

        Artisan::call('vendor:publish', ['--tag' => 'turbulence']);

        InstallManifest::files()->each(function ($files, $key) {
            $this->info("Installing {$key}...");

            foreach ($files as $source => $file) {
                if (File::exists($file['destination'])) {
                    $file['undo']();
                }

                $this->line("* Copy {$source} to {$file['destination']}");
                File::copy($source, $file['destination']);

                foreach ($file['rector'] as $rector) {
                    $this->line('* '.str_replace('Rector', '', Str::headline(last(explode('\\', $rector)))));
                    RunRector::rule($rector)->on($file['destination']);
                }
                $replacements = array_merge($file['replace'], InstallManifest::replace());
                foreach ($replacements as $search => $replace) {
                    $this->line(blank($replace) ? " * Remove {$search}" : "* Replace {$search} with {$replace}");
                    File::put($file['destination'], Str::replace(
                        array_keys($replacements),
                        array_values($replacements),
                        File::get($file['destination']))
                    );
                }
            }
        });

        $this->info('Adding role to the casts property on your User model');
        if (! RunRector::rule(AddRoleCastRector::class)->on(app_path('Models/User.php'))) {
            $this->error('We failed to do this for you. Please cast `role` to `ArtisanBuild\\Turbulence\\Enums\\UserRoles::class` in your User model');
        }
        else {
            $this->line('* Done');
        }
        $this->info('Adding the HasAccount trait to your User model');
        if (! RunRector::rule(AddHasAccountsTraitRector::class)->on(app_path('Models/User.php'))) {
            $this->error('We failed to do this for you. Please add `use App\\Traits\\HasAccounts;` to your User model');
        }
        else {
            $this->line('* Done');
        }

        return self::SUCCESS;
    }
}
