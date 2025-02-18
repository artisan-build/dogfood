<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class RelinkPackages extends Command
{
    protected $signature = 'relink-packages';

    protected $description = 'Re-link linked packages';

    public function handle(): int
    {
        $json = File::json(bench_path('linked.json'));

        foreach ($json as $package) {
            if (File::isDirectory(bench_path($package['directory']))) {
                Process::run('composer link '.bench_path($package['directory']));
            }
        }

        return self::SUCCESS;
    }
}
