<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Events;

use ArtisanBuild\Adverbs\Attributes\Inert;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Models\Member;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

#[Inert]
class MembersRequested extends Event
{
    public ?int $channel_id = null;
    public int $take = 25;
    public int $skip = 0;

    #[Once]
    public function handle()
    {
        if (null === $this->channel_id || ChannelState::load($this->channel_id)->type->isOpenChannel()) {
            return Member::query()
                ->skip($this->skip)
                ->take($this->take)
                ->get();
        }

        return Member::query()
            ->whereHas('channel_memberships', function ($query): void {
                $query->where('channel_id', $this->channel_id);
            })
            ->skip($this->skip)
            ->take($this->take)
            ->get();
    }

}
