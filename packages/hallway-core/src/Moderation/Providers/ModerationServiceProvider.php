<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Moderation\Providers;

use Illuminate\Support\ServiceProvider;

class ModerationServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void {}

    public function boot(): void {}
}
