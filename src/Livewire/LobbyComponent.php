<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Pages\Events\PageCreated;
use ArtisanBuild\Hallway\Pages\Models\Page;
use Livewire\Component;

class LobbyComponent extends Component
{
    public ?Page $lobby = null;
    protected string $template = 'hallway-flux::livewire.dashboard';

    public function mount(): void
    {
        $lobby = Page::where('is_lobby', true)->first();

        if (null === $lobby) {
            PageCreated::commit(
                title: 'Welcome to your new Hallway.fm Community!',
                slug: 'lobby',
                is_lobby: true,
                free_content: file_get_contents(__DIR__ . '/../../resources/markdown/install_lobby.md'),
            );
        }
    }

    public function render()
    {
        return view($this->template)->layout('hallway-flux::layouts.app');
    }
}
