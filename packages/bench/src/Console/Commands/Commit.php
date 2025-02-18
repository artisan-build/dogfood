<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use ArtisanBuild\Bench\Git\MakeCommit;
use ArtisanBuild\Bench\Git\PushChanges;
use ArtisanBuild\Bench\Git\StageAllChangedFiles;
use Exception;
use Illuminate\Console\Command;

class Commit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commit {--finished=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add all dirty files across the project and packages with a given commit message';

    /**
     * Execute the console command.
     */
    public function handle(
        StageAllChangedFiles $stageAllChangedFiles,
        MakeCommit $commit,
        PushChanges $pushChanges,
    ): int {
        $message = $this->ask('What did you do?');

        // Keep GitHub Actions from running on commits that aren't ready for review
        if (! $this->option('finished')) {
            $message .= ' #nodeploy';
        }

        try {
            ($stageAllChangedFiles)();
            ($commit)($message);
            ($pushChanges)();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        return self::SUCCESS;
    }
}
