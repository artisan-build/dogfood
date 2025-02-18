<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Console\Commands;

use ArtisanBuild\Bench\Git\CreateBranchInProjectAndPackages;
use ArtisanBuild\Bench\Git\EnsureProjectAndPackagesAreInACleanState;
use ArtisanBuild\Bench\Git\EnsureProjectAndPackagesAreOnMain;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class StartWorking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start-working';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up the project and linked packages to start working';

    /**
     * Execute the console command.
     */
    public function handle(
        EnsureProjectAndPackagesAreInACleanState $cleanState,
        EnsureProjectAndPackagesAreOnMain $onMain,
        CreateBranchInProjectAndPackages $createBranch,
    ) {
        try {
            ($cleanState)();
            ($onMain)();
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $branch = Str::kebab($this->ask('In about 4 words, what are we going to work on?'));

        if (! $this->confirm("We are about to create the branch {$branch} on the project and all linked packages. Does this sound good?")) {
            $this->fail('Fine then. Try again later.');
        }

        try {
            ($createBranch)($branch);
        } catch (Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->info("You are now on the branch {$branch} in this project and all linked packages. Have fun!");

        return self::SUCCESS;
    }
}
