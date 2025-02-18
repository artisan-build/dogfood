<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Payment\Providers;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void {}

    public function boot(): void {}
}
