<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class UnlinkPackages extends Command
{
    protected $signature = 'unlink-packages';

    protected $description = 'Unlink any linked packages';

    public function handle(): void
    {
        $json = File::json(bench_path('linked.json'));

        foreach ($json as $package) {
            if (File::isDirectory(bench_path($package['directory']))) {
                Process::run('composer unlink '.bench_path($package['directory']));
            }
        }
    }
}
