<?php

declare(strict_types=1);

use App\Livewire\DashboardComponent;
use ArtisanBuild\OpencodeClient\Livewire\OpencodeExplorer;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->get('/', fn () => view('welcome'));

// Temporarily remove auth for OpenCode testing
Route::get('opencode-explorer', OpencodeExplorer::class)->name('opencode-explorer');

Route::middleware([
    'auth:sanctum',
    'web',
    'verified',
])->group(function (): void {
    Route::get('dashboard', DashboardComponent::class)->name('dashboard');
});
