<?php

declare(strict_types=1);

namespace ArtisanBuild\HallwayFlux\Livewire;

use ArtisanBuild\Hallway\Pages\Events\LobbyPageRequested;
use ArtisanBuild\Hallway\Pages\Events\PageCreated;
use ArtisanBuild\Hallway\Support\Functions;
use Illuminate\Support\Facades\Context;
use Livewire\Component;

class LobbyComponent extends Component
{
    public $lobby;

    protected string $template = 'hallway-flux::livewire.lobby';

    public function mount(): void
    {
        $this->lobby = LobbyPageRequested::commit();

        if ($this->lobby === null) {
            if (! Functions::can(PageCreated::class, Context::get('active_member'))) {
                $this->template = 'hallway-flux::livewire.lobby_not_created';
            } else {
                $this->lobby = $this->createLobbyPage();
                $this->template = 'hallway-flux::livewire.lobby_creating';
            }
        }
    }

    public function checkForCompletion(): void
    {
        $this->template = 'hallway-flux::livewire.lobby';
    }

    public function createLobbyPage()
    {
        return PageCreated::commit(
            title: 'Welcome to your new Hallway.fm Community!',
            slug: 'lobby',
            is_lobby: true,
            free_content: file_get_contents(__DIR__.'/../../resources/markdown/install_lobby.md'),
        );
    }

    public function render()
    {
        return view($this->template)->layout('hallway-flux::layouts.app');
    }
}
