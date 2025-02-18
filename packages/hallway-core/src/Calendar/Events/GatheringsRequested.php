<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\Events;

use ArtisanBuild\Adverbs\Attributes\Inert;
use ArtisanBuild\Hallway\Calendar\Models\Gathering;
use Illuminate\Support\Collection;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

#[Inert]
class GatheringsRequested extends Event
{
    #[Once]
    public function handle(): Collection
    {
        return Gathering::orderBy('start')->where('start', '>', now()->startOfMonth())->get();
    }

}
