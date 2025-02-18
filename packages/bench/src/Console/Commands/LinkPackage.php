<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

class LinkPackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link-package {package}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Link a package to do local work on it';

    /**
     * @throws Exception
     */
    public function handle(): int
    {
        [$vendor, $package] = match (count(explode('/', $this->argument(key: 'package')))) {
            1 => ['artisan-build', $this->argument('package')],
            2 => explode('/', $this->argument(key: 'package')),
            default => throw new Exception('Package name must be in vendor/package format except'),
        };

        $folder = base_path('packages/'.$package);

        if (File::isDirectory($folder)) {
            throw_if(
                ! blank(Process::path($folder)->run('git status --porcelain')->output()),
                'You have uncommitted changes in the package you are trying to re-link',
            );

            throw_if(
                ! blank(Process::path($folder)->run('git log @{u}..')->output()),
                'You have un-pushed changes in the package you are trying to re-link',
            );
        }

        if (File::isDirectory($folder) &&
            ! $this->confirm('This package already exists in your directory. If you continue we will get a fresh copy from GitHub and re-link it. Any local changes will be lost. Do you want to do this?')) {
            return self::SUCCESS;
        }

        File::deleteDirectory($folder);

        $repository = Http::get("https://packagist.org/packages/{$vendor}/{$package}.json")->json('package.repository');

        if (blank($repository)) {
            $repository = $this->ask('This appears to be a private repository. Please paste in the URL of the repository and hit enter to continue.');
        }
        Process::path(base_path('packages'))->run("git clone {$repository}");

        Process::run('composer link packages/'.$package);

        return self::SUCCESS;

    }
}
