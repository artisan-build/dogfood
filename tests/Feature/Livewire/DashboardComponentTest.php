<?php

use App\Livewire\DashboardComponent;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(DashboardComponent::class)
        ->assertStatus(200);
});
