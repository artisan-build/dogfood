<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\States;

use ArtisanBuild\Hallway\Calendar\Enums\InvitationLevels;
use Carbon\Carbon;
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
}
