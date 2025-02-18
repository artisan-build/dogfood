<?php

namespace ArtisanBuild\GH\Commands;

use ArtisanBuild\GH\GH;
use Illuminate\Console\Command;

/**
 * DO NOT RUN THIS COMMAND!
 *
 * I'm just using this as a place where I can slap together operations from the GH commands defined here
 * to make sure they work as expected against the live API. At any given time it might do anything from creating a
 * repository to listing organizations. Whatever I happen to be playing with at the moment.
 */
class Sandbox extends Command
{
    protected $signature = 'gh:sandbox';

    protected $description = 'Just somewhere I can slap manual tests to make sure these various commands work';

    protected $hidden = true;

    public function handle(): int
    {
        $this->info(GH::repo('edgrosvenor/clitest')->unarchive());

        return self::SUCCESS;
    }
}
