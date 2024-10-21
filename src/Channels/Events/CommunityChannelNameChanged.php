<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Channels\Events;

use ArtisanBuild\Hallway\Channels\States\ChannelState;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\Traits\AuthorizesBasedOnMemberState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class CommunityChannelNameChanged extends Event
{
    use AuthorizesBasedOnMemberState;

    public array $authorized_member_roles = [
        MemberRoles::Owner,
        MemberRoles::Admin,
    ];

    #[StateId(ChannelState::class)]
    public int $channel_id;

    public string $name;

    public function apply(ChannelState $state): void
    {
        $state->name = $this->name;
    }

    public function handle(): void
    {
        // You think you're calling shots, you got the wrong number. I love Benjamin Franklin more than his own mother. - Lil Wayne
    }
}
