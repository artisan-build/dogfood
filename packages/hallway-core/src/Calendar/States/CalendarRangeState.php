<?php

declare(strict_types=1);

namespace ArtisanBuild\Hallway\Calendar\States;

use Carbon\Carbon;
use Thunk\Verbs\SingletonState;

class CalendarRangeState extends SingletonState
{
    public ?Carbon $first_gathering_start = null;

    public ?Carbon $last_gathering_end = null;
}
