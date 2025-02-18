<?php

namespace App\Livewire;

use App\View\Components\AppLayout;
use Illuminate\Support\Facades\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(AppLayout::class)]
class DashboardComponent extends Component
{
    public function mount()
    {
        View::share('sidebar_navigation', [
            [
                'href' => '#',
                'text' => 'Item 1',
                'icon' => 'check',
                'badge' => null,
            ],
        ]);
    }

    public function render()
    {

        return view('livewire.dashboard-component');
    }
}
