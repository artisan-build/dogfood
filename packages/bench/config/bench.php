<?php

declare(strict_types=1);

use ArtisanBuild\Bench\Actions\MigrateFresh;
use ArtisanBuild\Bench\Actions\SeedDatabase;
use ArtisanBuild\Bench\Database\PrebuiltData\Sqlite\DumpDatabaseForTesting;

return [
    'bench_directory' => base_path('packages'),
    'fresh-actions' => [
        MigrateFresh::class,
        SeedDatabase::class,
        DumpDatabaseForTesting::class,
    ],
    'run-once' => [
    ],
];
