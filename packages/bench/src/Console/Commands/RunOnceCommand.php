<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Pipeline;

class RunOnceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run-once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run idempotent actions upon deployment';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $messages = Pipeline::send([])->through(config('developer-tools.run-once'))->thenReturn();

        foreach ($messages as $message) {
            $this->info($message);
        }

        return self::SUCCESS;
    }
}
