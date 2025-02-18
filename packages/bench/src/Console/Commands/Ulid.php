<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class Ulid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ulid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy a fresh ulid to your clipboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Process::run('php artisan fresh-id ulid | pbcopy');
        $this->info('A fresh ulid has been copied to your clipboard');

        return self::SUCCESS;
    }
}
