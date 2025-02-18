<?php

namespace ArtisanBuild\Bench\Console\Commands\Project;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class CreateProjectCommand extends Command
{
    protected $signature = 'bench:create-project';

    protected $description = 'Set up a bunch of our defaults on a new project';

    public function handle()
    {
        $require = require __DIR__.'/../../../../stubs/project/require.php';
        $require_dev = require __DIR__.'/../../../../stubs/project/require-dev.php';
        $composer_scripts = require __DIR__.'/../../../../stubs/project/composer-scripts.php';

        $this->info('Adding dependencies...');
        Process::run('composer require '.implode(' ', $require))->output();
        $this->info('Adding dev dependencies...');
        Process::run('composer require --dev '.implode(' ', $require_dev))->output();

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        $this->info('Adding composer scripts...');
        $composer['scripts'] = array_merge($composer_scripts, data_get($composer, 'scripts', []));

        File::put(base_path('composer.json'), json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->info('Copying stubs...');

        foreach (['phpstan.neon', 'phpstan-baseline.neon'] as $stub) {
            File::put(base_path($stub), File::get(__DIR__.'/../../../../stubs/project/'.$stub));
        }

        return self::SUCCESS;
    }
}
