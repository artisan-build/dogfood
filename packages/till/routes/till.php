<?php

use ArtisanBuild\Till\Controllers\SubscribeController;
use ArtisanBuild\Till\Livewire\PricingSectionComponent;
use ArtisanBuild\Till\Middleware\RegisterIfNotAuthenticated;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', RegisterIfNotAuthenticated::class])->group(function (): void {
    Route::get(config('till.subscribe_uri').'/{plan_id}/{period}', SubscribeController::class)->name('till:subscribe');
    Route::get(config('till.pricing_uri'), PricingSectionComponent::class)->name('till:pricing');
});
