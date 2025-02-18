<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Members;

use ArtisanBuild\Adverbs\Traits\SimpleApply;
use ArtisanBuild\Hallway\Members\States\MemberState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class MemberTimezoneUpdated extends Event
{
    use SimpleApply;

    #[StateId(MemberState::class)]
    public int $member_id;
    public string $timezone;


}
