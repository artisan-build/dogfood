<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Providers;

use Illuminate\Support\ServiceProvider;
use Override;

class CalendarServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void {}

    public function boot(): void {}
}
