<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\States;

use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use ArtisanBuild\Hallway\Members\States\MemberState;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Thunk\Verbs\State;

class GatheringState extends State
{
    public string $title;

    public string $description;

    public Carbon $start;

    public Carbon $end;

    public ?Carbon $published_at = null;

    public ?Carbon $cancelled_at = null;

    public InvitationLevels $invitation_level;

    // Allow events to be added to channels
    public ?int $channel_id = null;

    public ?int $capacity = null;

    public ?string $url = null;

    public function forMember(?MemberState $member = null): static
    {
        $this->start = $this->start->setTimezone($member->timezone ?? Session::get('timezone', 'UTC'));
        $this->end = $this->end->setTimezone($member->timezone ?? Session::get('timezone', 'UTC'));

        return $this;
    }
}
