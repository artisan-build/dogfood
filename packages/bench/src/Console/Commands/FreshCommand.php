<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use ArtisanBuild\Bench\Attributes\ChatGPT;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class FreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the system for local development';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (App::isProduction()) {
            $this->error('You cannot run this command in production');

            return self::FAILURE;
        }

        $path = config('database.connections.sqlite.database');
        if (File::missing($path)) {
            $this->warn('The database does not exist.');
            $this->line('Creating an empty database at '.$path);
            File::put($path, '');
        }

        foreach (config('bench.fresh-actions') as $action) {
            app($action)();
        }

        $this->createDatabasesForParallelTesting();

        return self::SUCCESS;
    }

    private function createDatabasesForParallelTesting(): void
    {
        foreach (range(1, $this->getCpuCores()) as $item) {
            $file = database_path("testing.sqlite_test_{$item}");
            if (File::exists($file)) {
                File::delete($file);
            }
            File::copy(database_path('testing.sqlite'), $file);
        }
    }

    #[ChatGPT]
    private function getCpuCores()
    {
        $cores = 1; // Default to 1 core if detection fails

        if (mb_strtoupper(mb_substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $process = @popen('wmic cpu get NumberOfCores', 'rb');
            if ($process !== false) {
                fgets($process); // Skip first line
                $cores = (int) fgets($process);
                pclose($process);
            }
        } else {
            // Linux and Mac
            $command = mb_strtoupper(PHP_OS) === 'DARWIN' ? 'sysctl -n hw.ncpu' : 'nproc';
            $output = @shell_exec($command);
            $cores = $output ? (int) $output : 1;
        }

        return $cores;
    }
}
