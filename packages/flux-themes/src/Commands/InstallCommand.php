<?php

namespace ArtisanBuild\FluxThemes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'flux-themes:install {--force=}';

    protected $description = 'Initial install of the flux theme css and templates';

    public function handle(): int
    {
        if (! $this->option('force')) {
            if (File::exists(resource_path('views/components/layouts/app.php'))) {
                if (!$this->confirm('The app layout already exists. Do you want to overwrite it?')) {
                    return self::FAILURE;
                }
            }

            if (File::exists(resource_path('css/app.css'))) {
                if (!$this->confirm('The app.css file already exists. Do you want to overwrite it?')) {
                    return self::FAILURE;
                }
            }

            if (File::exists(resource_path('views/welcome.blade.php'))) {
                if (!$this->confirm('The welcome.blade.php file already exists. Do you want to overwrite it?')) {
                    return self::FAILURE;
                }
            }

            if (File::exists(resource_path('views/components/svg/logo.blade.php'))) {
                if (!$this->confirm('The logo.blade.php file already exists. Do you want to overwrite it?')) {
                    return self::FAILURE;
                }
            }

        }

        File::put(resource_path('css/app.css'), File::get(__DIR__.'/../../stubs/app.css.stub'));
        File::ensureDirectoryExists(resource_path('views/components/layouts'));
        File::put(resource_path('views/components/layouts/app.blade.php'), File::get(__DIR__.'/../../stubs/app.blade.php.stub'));
        File::put(resource_path('views/welcome.blade.php'), File::get(__DIR__.'/../../stubs/welcome.blade.php.stub'));
        File::put(resource_path('views/components/svg/logo.blade.php'), File::get(__DIR__.'/../../stubs/logo.blade.php.stub'));
        return self::SUCCESS;
    }
}
