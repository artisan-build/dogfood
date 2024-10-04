<?php

declare(strict_types=1);

use ArtisanBuild\HallwayFlux\Livewire\DashboardComponent;

Route::prefix(config('hallway-flux.route-prefix'))
    ->name(config('hallway-flux.route-name-prefix'))
    ->middleware(config('hallway-flux.middleware'))
    ->group(function (): void {
        Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
    });
