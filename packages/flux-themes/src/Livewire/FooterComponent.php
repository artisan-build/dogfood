<?php

namespace ArtisanBuild\FluxThemes\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\View\View;
use Livewire\Component;

class FooterComponent extends Component
{
    public array $data = [];

    public function mount(?string $copyright_holder = null)
    {
        $this->data = [
            'copyright_holder' => $copyright_holder ?? config('app.name'),
        ];
    }

    public function render(): Application|\Illuminate\Contracts\View\View|Factory|View|null
    {
        return view('flux-themes::livewire.footer');
    }
}
