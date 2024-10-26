<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Pages\Events;

use ArtisanBuild\Adverbs\Attributes\Inert;
use ArtisanBuild\Hallway\Pages\Models\Page;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

#[Inert]
class LobbyPageRequested extends Event
{
    #[Once]
    public function handle(): ?Page
    {
        return Page::where('is_lobby', true)->first();
    }
}
