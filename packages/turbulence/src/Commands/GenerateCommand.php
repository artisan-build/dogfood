<?php

namespace ArtisanBuild\Turbulence\Commands;

use Illuminate\Console\Command;

class GenerateCommand extends Command
{
    protected $signature = 'turbulence:generate {?thing}';

    public function handle(): int
    {
        // Ask the user for the name of the thing

        // Ask the user for the relationship type to each existing entity (Account or extends Group)
        // Create pivot table and model if needed for each of the relationships

        // Copy Models/Stub.php and Models/StubProfile.php, making substitutions and fixing namespace
        // Copy Traits/HasStubs making subs and fixing namespace

        return self::SUCCESS;
    }
}
