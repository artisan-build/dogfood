<?php

use App\Livewire\DashboardComponent;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->get('/', fn () => view('welcome'));

Route::middleware([
    'auth:sanctum',
    config('verbstream.auth_session'),
    'verified',
])->group(function (): void {
    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');
});
