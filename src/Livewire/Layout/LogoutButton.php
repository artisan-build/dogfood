<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire\Layout;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LogoutButton extends Component
{
    public function logout(): void
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect('/');
    }

    public function render()
    {
        return view('hallway-flux::layouts.logout-button');
    }
}
