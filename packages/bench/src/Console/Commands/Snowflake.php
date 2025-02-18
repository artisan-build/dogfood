<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class Snowflake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snowflake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy a new snowflake to the clipboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Process::run('php artisan fresh-id | pbcopy');
        $this->info('A fresh snowflake has been copied to your clipboard');

        return self::SUCCESS;
    }
}
