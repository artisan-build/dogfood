<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class HallwayServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::anonymousComponentPath(__DIR__ . '/../../resources/views/components', 'hallway');
    }
}
