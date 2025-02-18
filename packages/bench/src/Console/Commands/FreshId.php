<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FreshId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fresh-id {type=snowflake}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Echo a UUID or snowflake as string';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->argument('type') === 'ulid') {
            echo mb_strtolower(Str::ulid()->toString());

            return self::SUCCESS;
        }

        echo (string) snowflake_id();

        return self::SUCCESS;
    }
}
