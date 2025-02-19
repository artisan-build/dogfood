<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Events;

use ArtisanBuild\Adverbs\Attributes\Inert;
use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Models\Member;
use Illuminate\Support\Collection;
use Thunk\Verbs\Attributes\Hooks\Once;
use Thunk\Verbs\Event;

#[Inert]
class MembersRequested extends Event
{
    public ?int $channel_id = null;

    public int $take = 25;

    public int $skip = 0;

    #[Once]
    public function handle(): Collection
    {
        if ($this->channel_id === null || ChannelState::load($this->channel_id)->type->isOpenChannel()) {
            return Member::query()
                ->whereNotNull('role')
                ->where('role', '<>', MemberRoles::Guest->value)
                ->skip($this->skip)
                ->take($this->take)
                ->get();
        }

        return ChannelState::load($this->channel_id)->members();
    }
}
