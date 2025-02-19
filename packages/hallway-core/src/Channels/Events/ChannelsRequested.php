<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Adverbs\Attributes\Inert;
use ArtisanBuild\Hallway\Channels\Models\Channel;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

#[Inert]
class ChannelsRequested extends Event
{
    #[Once]
    public function handle()
    {
        return Channel::get()->map(fn ($channel) => $channel->verbs_state());
    }
}
