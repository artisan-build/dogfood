<?php

declare(strict_types=1);

namespace ArtisanBuild\Bench\Actions;

use Illuminate\Support\Facades\Artisan;

class SeedDatabase
{
    public function __invoke(): void
    {
        Artisan::call('db:seed');
    }
}
