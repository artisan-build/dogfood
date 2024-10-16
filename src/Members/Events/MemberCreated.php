<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members\Events;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Members\Enums\MemberRoles;
use ArtisanBuild\Hallway\Members\States\MemberState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class MemberCreated extends Event
{
    use SimpleApply;

    #[StateId(MemberState::class)]
    public ?int $member_id = null;

    public int $user_id;

    public MemberRoles $role = MemberRoles::Member;

    public string $handle;

    public string $display_name;


    public array $channel_ids = [];


}
