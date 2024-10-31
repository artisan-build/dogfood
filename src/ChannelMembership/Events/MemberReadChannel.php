<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\ChannelMembership\Events;

use ArtisanBuild\Hallway\Messages\Models\Message;
use Illuminate\Support\Collection;
use Thunk\Verbs\Event;

class MemberReadChannel extends Event
{
    public int $channel_id;

    public function handle(): Collection
    {
        return Message::where('channel_id', $this->channel_id)->whereNull('thread_id')->get();
    }
}
