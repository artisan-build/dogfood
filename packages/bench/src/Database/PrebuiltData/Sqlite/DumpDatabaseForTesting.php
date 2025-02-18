<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Database\PrebuiltData\Sqlite;

use Illuminate\Support\Facades\File;

class DumpDatabaseForTesting
{
    public function __invoke(): void
    {
        if (File::exists(database_path('testing.sqlite'))) {
            File::delete(database_path('testing.sqlite'));
        }
        File::copy(database_path('database.sqlite'), database_path('testing.sqlite'));
    }
}
