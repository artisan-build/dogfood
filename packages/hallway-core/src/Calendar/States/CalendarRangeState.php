<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\States;

use Carbon\Carbon;
use Thunk\Verbs\State;

class CalendarRangeState extends State
{
    public ?Carbon $first_gathering_start = null;

    public ?Carbon $last_gathering_end = null;
}
